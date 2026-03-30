import { chromium } from 'playwright-core';
import process from 'node:process';
import { existsSync } from 'node:fs';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const IS_LOCAL_BASE = /^https?:\/\/(127\.0\.0\.1|localhost)(:\d+)?/i.test(BASE_URL);
const DEFAULT_BROWSER_CANDIDATES = [
    process.env.PLAYWRIGHT_BROWSER_PATH,
    '/usr/bin/google-chrome',
    '/usr/bin/google-chrome-stable',
    '/usr/bin/chromium-browser',
    '/usr/bin/chromium',
].filter(Boolean);

const AUTH_EMAIL = process.env.SMOKE_AUTH_EMAIL || (IS_LOCAL_BASE ? 'partner.invitation@easycook.com' : '');
const AUTH_PASSWORD = process.env.SMOKE_AUTH_PASSWORD || (IS_LOCAL_BASE ? 'Omega12345!' : '');
const INVITATION_CODE = process.env.SMOKE_INVITATION_CODE || (IS_LOCAL_BASE ? 'CAPTAIN-TEST10' : '');

function resolveBrowserPath() {
    const browserPath = DEFAULT_BROWSER_CANDIDATES.find((candidate) => existsSync(candidate));

    if (!browserPath) {
        throw new Error('No supported Chrome/Chromium executable was found. Set PLAYWRIGHT_BROWSER_PATH before running the auth smoke script.');
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

function uniqueRegistrationIdentity() {
    const stamp = Date.now();

    return {
        email: `omega.release.${stamp}@gmail.com`,
        phone: `010${String(stamp).slice(-8)}`,
    };
}

async function resetClientState(page, route) {
    await page.goto(`${BASE_URL}${route}`, { waitUntil: 'domcontentloaded' });
    await page.evaluate(() => {
        localStorage.clear();
        sessionStorage.clear();
    });
    await page.reload({ waitUntil: 'domcontentloaded' });
}

async function savePlansSelection(page, { weeks, days }) {
    await resetClientState(page, '/plans');

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
    }, { weeks, days, planId: '1400' });

    await page.goto(`${BASE_URL}/checkout-page`, { waitUntil: 'domcontentloaded' });
}

async function evaluateCheckoutState(page) {
    return page.evaluate(() => {
        const subtotal = document.getElementById('woocommercePrice_sub_total')?.textContent?.trim() || '';
        const discount = document.getElementById('woocommercePrice_discount')?.textContent?.trim() || '';
        const vat = document.getElementById('woocommercePrice_vat')?.textContent?.trim() || '';
        const total = document.getElementById('woocommercePrice')?.textContent?.trim() || '';

        return {
            bodyText: document.body.innerText,
            subtotal,
            discount,
            vat,
            total,
            streetAddress: document.getElementById('street_address')?.value || '',
            city: document.getElementById('billing_address_city')?.value || '',
            standardStore: JSON.parse(localStorage.getItem('standerdPackages') || '{}'),
        };
    });
}

async function dismissSwalIfPresent(page) {
    const button = page.locator('.swal2-confirm');
    if (await button.count()) {
        await button.first().click().catch(() => null);
        await page.waitForTimeout(200);
    }
}

async function runScenario(browser, name, handler) {
    log(`• ${name}`);
    const context = await browser.newContext({
        baseURL: BASE_URL,
        viewport: { width: 1440, height: 1100 },
    });
    const page = await context.newPage();

    try {
        await handler(page);
        log('  passed');
    } finally {
        await context.close();
    }
}

async function ensureBillingAddress(page) {
    const city = page.locator('#billing_address_city');
    const street = page.locator('#street_address');

    if ((await city.inputValue()) === '') {
        await city.selectOption('Cairo');
    }

    if ((await street.inputValue()).trim() === '') {
        await street.fill('Captain Gym HQ, Cairo');
    }
}

async function loginAndReturnToCheckout(page) {
    assert(AUTH_EMAIL && AUTH_PASSWORD, 'SMOKE_AUTH_EMAIL and SMOKE_AUTH_PASSWORD are required for auth smoke outside local mode.');

    await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded' });
    await page.locator('input[name="email"]').fill(AUTH_EMAIL);
    await page.locator('input[name="password"]').fill(AUTH_PASSWORD);
    await page.getByRole('button', { name: /Sign in and continue/i }).click();
    await page.waitForURL('**/checkout-page');

    const state = await evaluateCheckoutState(page);
    assert(!state.bodyText.includes('Pricing Error'), 'Checkout shows Pricing Error after login.');
    assert(Object.keys(state.standardStore || {}).length > 0, 'Plan selection was not preserved through login.');
}

async function registrationToCheckout(page) {
    assert(INVITATION_CODE, 'SMOKE_INVITATION_CODE is required for registration smoke outside local mode.');

    const identity = uniqueRegistrationIdentity();

    await savePlansSelection(page, { weeks: 3, days: 6 });
    await page.goto(`${BASE_URL}/register_new_client`, { waitUntil: 'domcontentloaded' });
    await page.locator('#invitationCodeInput').fill(INVITATION_CODE);
    await page.locator('#captureInvitationBtn').click();
    await page.waitForTimeout(500);
    await dismissSwalIfPresent(page);

    await page.locator('#first_name').fill('Omega');
    await page.locator('#last_name').fill('Release');
    await page.locator('#email').fill(identity.email);
    await page.locator('#phone').fill(identity.phone);
    await page.locator('#referralSourceSelect').selectOption('captain_gym');
    await page.locator('#street_address').fill('Omega Test Lane 12');
    await page.locator('#town_city').fill('Cairo');
    await page.locator('#password').fill('Omega12345!');
    await page.locator('#password_confirmation').fill('Omega12345!');
    await page.getByRole('button', { name: /Continue to checkout/i }).click();
    await page.waitForURL('**/checkout-page');

    const state = await evaluateCheckoutState(page);
    assert(!state.bodyText.includes('Pricing Error'), 'Checkout shows Pricing Error after registration.');
    assert(Object.keys(state.standardStore || {}).length > 0, 'Plan selection was not preserved through registration.');
}

async function authenticatedPaymentBoundary(page) {
    await savePlansSelection(page, { weeks: 4, days: 6 });
    await loginAndReturnToCheckout(page);
    await ensureBillingAddress(page);

    let paymentInitPayload = null;
    await page.route('**/payment-initiate', async (route) => {
        paymentInitPayload = route.request().postDataJSON();
        await route.fulfill({
            status: 200,
            contentType: 'application/json',
            body: JSON.stringify({
                status: 'success',
                payment_url: `${BASE_URL}/checkout-page?payment_mock=1`,
            }),
        });
    });

    await page.locator('#pay-now2').click();
    await page.waitForURL('**/checkout-page?payment_mock=1');
    assert(Array.isArray(paymentInitPayload?.subscription_ids) && paymentInitPayload.subscription_ids.length > 0, 'Payment initiation did not receive subscription IDs.');
}

async function main() {
    const browser = await chromium.launch({
        executablePath: resolveBrowserPath(),
        headless: process.env.HEADLESS !== 'false',
        args: ['--no-sandbox', '--disable-dev-shm-usage'],
    });

    try {
        log('Checkout Auth Smoke');
        log(`Base URL: ${BASE_URL}`);

        await runScenario(browser, 'registration page preserves plan context and redirects to /checkout-page', async (page) => {
            await registrationToCheckout(page);
        });

        await runScenario(browser, 'login returns to checkout and reaches mocked payment entry boundary', async (page) => {
            await authenticatedPaymentBoundary(page);
        });

        log('');
        log('Auth smoke completed successfully.');
    } finally {
        await browser.close();
    }
}

main().catch((error) => {
    console.error(`Auth smoke failed: ${error.message}`);
    process.exitCode = 1;
});
