<script>
    window.EasyCookSelectionStorage = (() => {
        const STANDARD_KEY = 'standerdPackages';
        const CUSTOMIZE_KEY = 'customizeMealsSave';
        const LEGACY_CUSTOMIZE_KEYS = ['customizeMeals', 'meals'];
        const RECOVERY_KEY = 'easyCookSelectionRecovery';
        const WEEK_KEY_PATTERN = /^week\d+$/i;
        const rawLocalStorageSetItem = Storage.prototype.setItem.bind(window.localStorage);
        const rawLocalStorageRemoveItem = Storage.prototype.removeItem.bind(window.localStorage);

        function isPlainObject(value) {
            return value !== null && typeof value === 'object' && !Array.isArray(value);
        }

        function readJson(key) {
            try {
                const raw = window.localStorage.getItem(key);
                if (!raw) {
                    return {};
                }

                const parsed = JSON.parse(raw);
                return isPlainObject(parsed) ? parsed : {};
            } catch (error) {
                return {};
            }
        }

        function writeJson(key, value) {
            const payload = isPlainObject(value) ? value : {};

            if (Object.keys(payload).length === 0) {
                rawLocalStorageRemoveItem(key);
                return;
            }

            rawLocalStorageSetItem(key, JSON.stringify(payload));
        }

        function removeKeys(keys) {
            keys.forEach((key) => rawLocalStorageRemoveItem(key));
        }

        function toPositiveInt(value) {
            const parsed = Number(value);

            if (!Number.isFinite(parsed) || parsed <= 0) {
                return null;
            }

            return Math.trunc(parsed);
        }

        function isSupportedDaysCount(value) {
            return value === 5 || value === 6;
        }

        function extractWeekKeys(planData) {
            return Object.keys(planData || {})
                .filter((key) => WEEK_KEY_PATTERN.test(key))
                .sort((left, right) => {
                    const leftIndex = Number(left.replace(/\D+/g, '')) || 0;
                    const rightIndex = Number(right.replace(/\D+/g, '')) || 0;

                    return leftIndex - rightIndex;
                });
        }

        function normalizeMealId(mealId) {
            if (typeof mealId !== 'string') {
                return null;
            }

            const trimmed = mealId.trim();

            if (!trimmed || !trimmed.toLowerCase().includes('week')) {
                return null;
            }

            return trimmed;
        }

        function normalizeDayMeals(dayValue) {
            const normalized = {};

            if (typeof dayValue === 'string') {
                const normalizedMealId = normalizeMealId(dayValue);
                if (!normalizedMealId) {
                    return normalized;
                }

                const parts = normalizedMealId.split('_');
                const mealType = parts[3];
                if (mealType) {
                    normalized[String(mealType).toLowerCase()] = normalizedMealId;
                }

                return normalized;
            }

            if (!isPlainObject(dayValue)) {
                return normalized;
            }

            Object.entries(dayValue).forEach(([mealType, mealValue]) => {
                if (typeof mealValue === 'string') {
                    const normalizedMealId = normalizeMealId(mealValue);
                    if (normalizedMealId) {
                        normalized[String(mealType).toLowerCase()] = normalizedMealId;
                    }
                    return;
                }

                if (isPlainObject(mealValue) && typeof mealValue.id === 'string') {
                    const normalizedMealId = normalizeMealId(mealValue.id);
                    if (normalizedMealId) {
                        normalized[String(mealType).toLowerCase()] = normalizedMealId;
                    }
                }
            });

            return normalized;
        }

        function normalizeWeeks(planData) {
            const normalized = {};

            extractWeekKeys(planData).forEach((weekKey) => {
                const weekValue = planData[weekKey];
                if (!isPlainObject(weekValue)) {
                    return;
                }

                const normalizedWeek = {};

                Object.entries(weekValue).forEach(([dayKey, dayValue]) => {
                    const normalizedDay = normalizeDayMeals(dayValue);
                    if (Object.keys(normalizedDay).length > 0) {
                        normalizedWeek[String(dayKey).toLowerCase()] = normalizedDay;
                    }
                });

                if (Object.keys(normalizedWeek).length > 0) {
                    normalized[weekKey] = normalizedWeek;
                }
            });

            return normalized;
        }

        function normalizePackage(planId, planData, type) {
            if (!isPlainObject(planData)) {
                return null;
            }

            const normalizedWeeks = normalizeWeeks(planData);
            const weekKeys = extractWeekKeys(normalizedWeeks);

            if (weekKeys.length === 0) {
                return null;
            }

            const firstWeekData = normalizedWeeks[weekKeys[0]] || {};
            const derivedDays = Object.keys(firstWeekData).length;
            const explicitDays = toPositiveInt(planData.days);
            const days = isSupportedDaysCount(derivedDays)
                ? derivedDays
                : (isSupportedDaysCount(explicitDays) ? explicitDays : null);

            if (!days) {
                return null;
            }

            const explicitWeeksCount = toPositiveInt(planData.weeks_count ?? planData.weeks);
            const actualWeeksCount = weekKeys.length;
            const weeksCount = actualWeeksCount > 0
                ? actualWeeksCount
                : (explicitWeeksCount ?? null);

            if (!weeksCount) {
                return null;
            }

            const normalized = {
                days,
                weeks_count: weeksCount,
                packageName: typeof planData.packageName === 'string' && planData.packageName.trim()
                    ? planData.packageName.trim()
                    : `${type === 'customized' ? 'Customized ' : ''}${planId} Calories`,
                ...normalizedWeeks,
            };

            if (type === 'standard') {
                const priceWithoutVat = Number(planData.price_without_vat);
                const priceWithVat = Number(planData.price_with_vat);

                if (Number.isFinite(priceWithoutVat) && priceWithoutVat >= 0) {
                    normalized.price_without_vat = priceWithoutVat;
                }

                if (Number.isFinite(priceWithVat) && priceWithVat >= 0) {
                    normalized.price_with_vat = priceWithVat;
                }
            }

            return normalized;
        }

        function normalizeStore(rawStore, type, recoveryMessages) {
            const normalizedStore = {};

            Object.entries(rawStore || {}).forEach(([planId, planData]) => {
                const normalizedPackage = normalizePackage(planId, planData, type);

                if (normalizedPackage) {
                    normalizedStore[planId] = normalizedPackage;
                    return;
                }

                recoveryMessages.push(`${type} ${planId}`);
            });

            return normalizedStore;
        }

        function mergeCustomizeStores(recoveryMessages) {
            const canonicalStore = normalizeStore(readJson(CUSTOMIZE_KEY), 'customized', recoveryMessages);
            const mergedStore = {
                ...canonicalStore,
            };

            LEGACY_CUSTOMIZE_KEYS.forEach((key) => {
                const legacyStore = normalizeStore(readJson(key), 'customized', recoveryMessages);

                Object.entries(legacyStore).forEach(([planId, planData]) => {
                    if (!mergedStore[planId]) {
                        mergedStore[planId] = planData;
                    }
                });
            });

            return mergedStore;
        }

        function persistRecovery(recoveryMessages) {
            if (recoveryMessages.length === 0) {
                window.sessionStorage.removeItem(RECOVERY_KEY);
                return;
            }

            window.sessionStorage.setItem(
                RECOVERY_KEY,
                'Some saved selections were rebuilt to match the current package format. Please review your checkout before payment.'
            );
        }

        function normalizeAllSelectionStorage() {
            const recoveryMessages = [];
            const standardStore = normalizeStore(readJson(STANDARD_KEY), 'standard', recoveryMessages);
            const customizeStore = mergeCustomizeStores(recoveryMessages);

            writeJson(STANDARD_KEY, standardStore);
            writeJson(CUSTOMIZE_KEY, customizeStore);
            removeKeys(LEGACY_CUSTOMIZE_KEYS);
            persistRecovery(recoveryMessages);

            return {
                standardStore,
                customizeStore,
                recoveryMessages,
            };
        }

        function consumeRecoveryMessage() {
            const message = window.sessionStorage.getItem(RECOVERY_KEY);
            if (message) {
                window.sessionStorage.removeItem(RECOVERY_KEY);
            }

            return message;
        }

        function readStandardStore() {
            return readJson(STANDARD_KEY);
        }

        function readCustomizeStore() {
            return readJson(CUSTOMIZE_KEY);
        }

        function extractMeals(planData) {
            const meals = [];

            extractWeekKeys(planData).forEach((weekKey) => {
                const weekObj = planData?.[weekKey] || {};

                Object.entries(weekObj).forEach(([dayKey, dayObj]) => {
                    Object.entries(dayObj || {}).forEach(([mealType, mealId]) => {
                        const normalizedMealId = normalizeMealId(mealId);
                        if (!normalizedMealId) {
                            return;
                        }

                        meals.push({
                            meal_id: normalizedMealId.replace(/_[a-z]{2}$/i, ''),
                            week: weekKey,
                            day: String(dayKey).toLowerCase(),
                            meal_type: String(mealType).toLowerCase(),
                        });
                    });
                });
            });

            return meals;
        }

        function resolveStoredWeeksCount(planData) {
            const explicitWeeksCount = toPositiveInt(planData?.weeks_count);
            if (explicitWeeksCount) {
                return explicitWeeksCount;
            }

            return extractWeekKeys(planData).length;
        }

        function resolveStoredDaysPerWeek(planData) {
            const explicitDays = toPositiveInt(planData?.days);
            if (isSupportedDaysCount(explicitDays)) {
                return explicitDays;
            }

            const weekKeys = extractWeekKeys(planData);
            if (weekKeys.length === 0) {
                return 0;
            }

            return Object.keys(planData?.[weekKeys[0]] || {}).length;
        }

        function hasPlanSelectionContext() {
            return Object.keys(readStandardStore()).length > 0 || Object.keys(readCustomizeStore()).length > 0;
        }

        function normalizePlanId(planId) {
            return String(planId || '').replace(/\D+/g, '');
        }

        function buildSubscription(planId, planData, type, userId = window.userId ?? null) {
            const daysPerWeek = resolveStoredDaysPerWeek(planData);
            const weeksCount = resolveStoredWeeksCount(planData);

            if (!daysPerWeek || !weeksCount) {
                return null;
            }

            return {
                user_id: userId,
                plan_id: planId,
                weeks_count: weeksCount,
                days_per_week: daysPerWeek,
                meals: extractMeals(planData),
                type,
                package_name: planData?.packageName || `${normalizePlanId(planId)} Calories`,
            };
        }

        function collectSubscriptions(userId = window.userId ?? null) {
            normalizeAllSelectionStorage();

            const subscriptions = [];

            Object.entries(readCustomizeStore()).forEach(([planId, planData]) => {
                const subscription = buildSubscription(planId, planData, 'customized', userId);
                if (subscription) {
                    subscriptions.push(subscription);
                }
            });

            Object.entries(readStandardStore()).forEach(([planId, planData]) => {
                const subscription = buildSubscription(planId, planData, 'standard', userId);
                if (subscription) {
                    subscriptions.push(subscription);
                }
            });

            return subscriptions;
        }

        return {
            STANDARD_KEY,
            CUSTOMIZE_KEY,
            LEGACY_CUSTOMIZE_KEYS,
            extractWeekKeys,
            extractMeals,
            readJson,
            writeJson,
            readStandardStore,
            readCustomizeStore,
            resolveStoredWeeksCount,
            resolveStoredDaysPerWeek,
            normalizePackage,
            normalizeStandardPackage: (planId, planData) => normalizePackage(planId, planData, 'standard'),
            normalizeCustomizePackage: (planId, planData) => normalizePackage(planId, planData, 'customized'),
            normalizeAllSelectionStorage,
            consumeRecoveryMessage,
            hasPlanSelectionContext,
            buildSubscription,
            collectSubscriptions,
        };
    })();

    window.EasyCookSelectionStorage.normalizeAllSelectionStorage();
</script>
