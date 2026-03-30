import { chromium } from 'playwright-core';
import process from 'node:process';
import { existsSync } from 'node:fs';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const DEFAULT_BROWSER_CANDIDATES = [
    process.env.PLAYWRIGHT_BROWSER_PATH,
    '/usr/bin/google-chrome',
    '/usr/bin/google-chrome-stable',
    '/usr/bin/chromium-browser',
    '/usr/bin/chromium',
].filter(Boolean);

function resolveBrowserPath() {
    const browserPath = DEFAULT_BROWSER_CANDIDATES.find((candidate) => existsSync(candidate));

    if (!browserPath) {
        throw new Error('No supported Chrome/Chromium executable was found. Set PLAYWRIGHT_BROWSER_PATH before running the smoke script.');
    }

    return browserPath;
}

function log(message) {
    process.stdout.write(`${message}\n`);
}

function assert(condition, message) {
    if (!condition) {
        throw new Error(message);
    }
}

function parseCurrency(text) {
    return Number(String(text || '').replace(/[^\d.]+/g, '')) || 0;
}

async function clickByText(page, selector, text) {
    const clicked = await page.evaluate(({ selector, text }) => {
        const normalize = (value) => String(value || '').replace(/\s+/g, ' ').trim();
        const target = normalize(text);
        const element = Array.from(document.querySelectorAll(selector)).find((candidate) => {
            return normalize(candidate.textContent).includes(target);
        });

        if (!element) {
            return false;
        }

        element.scrollIntoView({ block: 'center', inline: 'center' });
        element.click();
        return true;
    }, { selector, text });

    assert(clicked, `Could not click "${text}" inside "${selector}".`);
    await page.waitForTimeout(250);
}

async function resetClientState(page, route) {
    await page.goto(`${BASE_URL}${route}`, { waitUntil: 'domcontentloaded' });
    await page.evaluate(() => {
        localStorage.clear();
        sessionStorage.clear();
    });
    await page.reload({ waitUntil: 'domcontentloaded' });
}

async function evaluateCheckoutState(page) {
    return page.evaluate(() => {
        const subtotal = document.getElementById('woocommercePrice_sub_total')?.textContent?.trim() || '';
        const discount = document.getElementById('woocommercePrice_discount')?.textContent?.trim() || '';
        const vat = document.getElementById('woocommercePrice_vat')?.textContent?.trim() || '';
        const total = document.getElementById('woocommercePrice')?.textContent?.trim() || '';
        const orderRowTotals = Array.from(
            document.querySelectorAll('#checkout-order-body tr.cart_item td[data-title="Total"] .amountstanderd bdi')
        ).map((element) => element.textContent?.trim() || '');

        return {
            bodyText: document.body.innerText,
            subtotal,
            discount,
            vat,
            total,
            orderRowTotals,
            subscriptions: typeof collectSubscriptions === 'function' ? collectSubscriptions() : [],
            pricingMatrix: window.checkoutPricingMatrix || {},
            standardStore: JSON.parse(localStorage.getItem('standerdPackages') || '{}'),
            customizeStore: JSON.parse(localStorage.getItem('customizeMealsSave') || '{}'),
        };
    });
}

function assertCheckoutScenario(state, { type, weeks, days, planId = '1400', expectedPackageName }) {
    assert(!state.bodyText.includes('Pricing Error'), 'Checkout still shows Pricing Error.');
    assert(state.subscriptions.length === 1, `Expected exactly one subscription in checkout, found ${state.subscriptions.length}.`);

    const [subscription] = state.subscriptions;
    assert(String(subscription.plan_id) === String(planId), `Expected plan ${planId}, received ${subscription.plan_id}.`);
    assert(subscription.type === type, `Expected subscription type ${type}, received ${subscription.type}.`);
    assert(Number(subscription.weeks_count) === weeks, `Expected ${weeks} weeks, received ${subscription.weeks_count}.`);
    assert(Number(subscription.days_per_week) === days, `Expected ${days} delivery days, received ${subscription.days_per_week}.`);

    if (expectedPackageName) {
        assert(
            String(subscription.package_name || '') === expectedPackageName,
            `Expected package name "${expectedPackageName}", received "${subscription.package_name}".`
        );
    }

    const tier = state.pricingMatrix?.[planId]?.[String(days)]?.[String(weeks)];
    assert(tier, `Checkout pricing matrix is missing ${planId}/${days}/${weeks}.`);

    const subtotal = parseCurrency(state.subtotal);
    const discount = parseCurrency(state.discount);
    const vat = parseCurrency(state.vat);
    const total = parseCurrency(state.total);
    const rowTotals = state.orderRowTotals.map((value) => parseCurrency(value));
    const expectedSubtotal = Number(tier.without_vat || 0);
    const expectedTotal = Number(tier.with_vat || 0);
    const expectedVat = Number((expectedTotal - expectedSubtotal).toFixed(2));

    assert(Math.abs(subtotal - expectedSubtotal) < 0.01, `Expected subtotal ${expectedSubtotal}, received ${subtotal}.`);
    assert(Math.abs(discount - 0) < 0.01, `Expected discount 0, received ${discount}.`);
    assert(Math.abs(vat - expectedVat) < 0.01, `Expected VAT ${expectedVat}, received ${vat}.`);
    assert(Math.abs(total - expectedTotal) < 0.01, `Expected total ${expectedTotal}, received ${total}.`);
    assert(rowTotals.length === 1, `Expected one order row total, received ${rowTotals.length}.`);
    assert(Math.abs(rowTotals[0] - expectedTotal) < 0.01, `Expected order row total ${expectedTotal}, received ${rowTotals[0]}.`);
    assert(Math.abs((subtotal - discount + vat) - total) < 0.01, `Subtotal - discount + VAT should equal total, received ${subtotal} - ${discount} + ${vat} != ${total}.`);
}

function assertStandardStorage(standardStore, { weeks, days, planId = '1400' }) {
    const plan = standardStore?.[planId];
    assert(plan, `No standard package was saved for plan ${planId}.`);
    assert(Number(plan.weeks_count) === weeks, `Standard storage weeks_count should be ${weeks}, received ${plan.weeks_count}.`);
    assert(Number(plan.days) === days, `Standard storage days should be ${days}, received ${plan.days}.`);

    const savedWeekKeys = Object.keys(plan).filter((key) => /^week\d+$/i.test(key));
    assert(savedWeekKeys.length === weeks, `Standard storage should contain ${weeks} saved weeks, received ${savedWeekKeys.length}.`);
}

function assertCustomizeStorage(customizeStore, { weeks, days, planId = '1400' }) {
    const plan = customizeStore?.[planId];
    assert(plan, `No customized package was saved for plan ${planId}.`);
    assert(Number(plan.weeks_count) === weeks, `Customized storage weeks_count should be ${weeks}, received ${plan.weeks_count}.`);
    assert(Number(plan.days) === days, `Customized storage days should be ${days}, received ${plan.days}.`);

    const savedWeekKeys = Object.keys(plan).filter((key) => /^week\d+$/i.test(key));
    assert(savedWeekKeys.length === weeks, `Customized storage should contain ${weeks} saved weeks, received ${savedWeekKeys.length}.`);
}

async function seedStandardStore(page, route, { weeks, days, planId = '1400' }) {
    await resetClientState(page, route);

    await page.evaluate(async ({ weeks, days, planId }) => {
        const allData = await fetch('/standred_weeks.json').then((response) => response.json());
        const planCatalog = window.plansPricingCatalog?.[String(planId)] || window.plansPricingCatalog?.[Number(planId)];
        const tier = planCatalog?.[String(weeks)]?.[String(days)] || planCatalog?.[Number(weeks)]?.[Number(days)];

        if (!tier) {
            throw new Error(`Missing plan tier for ${planId}/${weeks}/${days}`);
        }

        const daysList = days === 6
            ? ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday']
            : ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        const newWeeks = {};

        for (let index = 1; index <= weeks; index += 1) {
            const weekKey = `week${index}`;
            const weekPlan = allData?.[planId]?.[weekKey] || {};
            const filteredWeek = {};

            daysList.forEach((day) => {
                filteredWeek[day] = {};
                Object.entries(weekPlan?.[day] || {}).forEach(([mealType, mealArr]) => {
                    const meal = (mealArr.en || [])[0];
                    if (meal?.id) {
                        filteredWeek[day][mealType] = meal.id;
                    }
                });
            });

            newWeeks[weekKey] = filteredWeek;
        }

        localStorage.setItem('standerdPackages', JSON.stringify({
            [planId]: {
                days,
                weeks_count: weeks,
                packageName: 'Weight Loss : 1300 - 1500kcal',
                price_without_vat: Number(tier.without_vat || 0),
                price_with_vat: Number(tier.with_vat || 0),
                ...newWeeks,
            },
        }));
    }, { weeks, days, planId });

    await page.goto(`${BASE_URL}/checkout-page`, { waitUntil: 'domcontentloaded' });

    const state = await evaluateCheckoutState(page);
    assertStandardStorage(state.standardStore, { weeks, days });
    assertCheckoutScenario(state, {
        type: 'standard',
        weeks,
        days,
        expectedPackageName: 'Weight Loss : 1300 - 1500kcal',
    });
}

async function savePlan2Standard(page, { weeks, days }) {
    await seedStandardStore(page, '/plan_2', { weeks, days });
}

async function seedPlan2Customize(page, { weeks, days, planId = '1400' }) {
    await resetClientState(page, '/plan_2');
    await page.evaluate(() => document.querySelector('[data-type="customize"]')?.click());
    await page.waitForTimeout(400);

    await clickByText(page, '#main-meal-tab-customize .tap-week', `${weeks} weeks`);

    await page.evaluate(async ({ weeks, days, planId }) => {
        window.weekDuration = weeks;
        window.dayDuration = days;

        const mealTypes = ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2'];
        const dayList = days === 6
            ? ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday']
            : ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        const data = await fetch('/customized.json').then((response) => response.json());
        const store = { [planId]: {} };

        for (let weekIndex = 1; weekIndex <= weeks; weekIndex += 1) {
            const weekKey = `week${weekIndex}`;
            store[planId][weekKey] = {};

            dayList.forEach((day) => {
                store[planId][weekKey][day] = {};

                mealTypes.forEach((mealType) => {
                    const firstMeal = data?.[planId]?.[weekKey]?.[day]?.[mealType]?.en?.[0];
                    if (firstMeal?.id) {
                        store[planId][weekKey][day][mealType] = firstMeal.id;
                    }
                });
            });
        }

        localStorage.setItem('customizeMeals', JSON.stringify(store));
    }, { weeks, days, planId });

    await page.locator('button.save-button').click({ force: true });
    await page.waitForTimeout(600);
    await page.goto(`${BASE_URL}/checkout-page`, { waitUntil: 'domcontentloaded' });

    const state = await evaluateCheckoutState(page);
    assertCustomizeStorage(state.customizeStore, { weeks, days, planId });
    assertCheckoutScenario(state, {
        type: 'customized',
        weeks,
        days,
        planId,
        expectedPackageName: `Customized ${planId} Calories`,
    });
}

async function seedPlansCustomize(page, { weeks, days, planId = '1400' }) {
    await resetClientState(page, '/plans');

    await page.evaluate(async ({ weeks, days, planId }) => {
        const mealTypes = ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2'];
        const dayList = days === 6
            ? ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday']
            : ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        const data = await fetch('/customized.json').then((response) => response.json());
        const store = {
            [planId]: {
                days,
                weeks_count: weeks,
                packageName: `Customized ${planId} Calories`,
            },
        };

        for (let weekIndex = 1; weekIndex <= weeks; weekIndex += 1) {
            const weekKey = `week${weekIndex}`;
            store[planId][weekKey] = {};

            dayList.forEach((day) => {
                store[planId][weekKey][day] = {};

                mealTypes.forEach((mealType) => {
                    const firstMeal = data?.[planId]?.[weekKey]?.[day]?.[mealType]?.en?.[0];
                    if (firstMeal?.id) {
                        store[planId][weekKey][day][mealType] = firstMeal.id;
                    }
                });
            });
        }

        localStorage.setItem('customizeMealsSave', JSON.stringify(store));
    }, { weeks, days, planId });

    await page.goto(`${BASE_URL}/checkout-page`, { waitUntil: 'domcontentloaded' });

    const state = await evaluateCheckoutState(page);
    assertCustomizeStorage(state.customizeStore, { weeks, days, planId });
    assertCheckoutScenario(state, {
        type: 'customized',
        weeks,
        days,
        planId,
        expectedPackageName: `Customized ${planId} Calories`,
    });
}

async function runScenario(page, name, handler) {
    log(`• ${name}`);
    await handler();
    log(`  passed`);
}

async function main() {
    const browser = await chromium.launch({
        executablePath: resolveBrowserPath(),
        headless: process.env.HEADLESS !== 'false',
        args: ['--no-sandbox', '--disable-dev-shm-usage'],
    });

    const context = await browser.newContext({
        baseURL: BASE_URL,
        viewport: { width: 1440, height: 1100 },
    });
    const page = await context.newPage();

    const consoleErrors = [];
    page.on('console', (message) => {
        if (message.type() === 'error') {
            consoleErrors.push(message.text());
        }
    });

    try {
        log('Checkout Storage Smoke');
        log(`Base URL: ${BASE_URL}`);

        await runScenario(page, '/plans standard monthly 24 days', async () => {
            await seedStandardStore(page, '/plans', { weeks: 4, days: 6 });
        });

        await runScenario(page, '/plans standard monthly 20 days', async () => {
            await seedStandardStore(page, '/plans', { weeks: 4, days: 5 });
        });

        await runScenario(page, '/plans standard 3 weeks 18 days', async () => {
            await seedStandardStore(page, '/plans', { weeks: 3, days: 6 });
        });

        await runScenario(page, '/plans standard 3 weeks 15 days', async () => {
            await seedStandardStore(page, '/plans', { weeks: 3, days: 5 });
        });

        await runScenario(page, '/plan_2 standard monthly 24 days', async () => {
            await savePlan2Standard(page, { weeks: 4, days: 6 });
        });

        await runScenario(page, '/plan_2 standard monthly 20 days', async () => {
            await savePlan2Standard(page, { weeks: 4, days: 5 });
        });

        await runScenario(page, '/plan_2 standard 3 weeks 18 days', async () => {
            await savePlan2Standard(page, { weeks: 3, days: 6 });
        });

        await runScenario(page, '/plan_2 standard 3 weeks 15 days', async () => {
            await savePlan2Standard(page, { weeks: 3, days: 5 });
        });

        await runScenario(page, '/plans customized 3 weeks 18 days', async () => {
            await seedPlansCustomize(page, { weeks: 3, days: 6 });
        });

        await runScenario(page, '/plan_2 customized 3 weeks 18 days', async () => {
            await seedPlan2Customize(page, { weeks: 3, days: 6 });
        });

        if (consoleErrors.length > 0) {
            log('');
            log('Console errors seen during smoke (non-blocking):');
            consoleErrors.slice(0, 12).forEach((entry) => log(`- ${entry}`));
        }

        log('');
        log('Smoke completed successfully.');
    } finally {
        await context.close();
        await browser.close();
    }
}

main().catch((error) => {
    console.error(`Smoke failed: ${error.message}`);
    process.exitCode = 1;
});
