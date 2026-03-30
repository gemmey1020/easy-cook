let watchId = null;

// فتح أو إنشاء قاعدة بيانات IndexedDB
function openDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('LocationDB', 1);

        request.onerror = (event) => {
            reject('Error opening IndexedDB:', event.target.errorCode);
        };

        request.onsuccess = (event) => {
            resolve(event.target.result);
        };

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('locations')) {
                db.createObjectStore('locations', { autoIncrement: true });
            }
        };
    });
}

// تخزين البيانات داخل IndexedDB
function storeLocationInDB(latitude, longitude, timestamp) {
    openDatabase()
        .then((db) => {
            const transaction = db.transaction('locations', 'readwrite');
            const store = transaction.objectStore('locations');
            store.add({ latitude, longitude, timestamp });

            transaction.oncomplete = () => {
                console.log('Location stored successfully in IndexedDB.');
            };

            transaction.onerror = (event) => {
                console.error('Error storing location:', event.target.error);
            };
        })
        .catch((error) => console.error(error));
}

// مزامنة البيانات مع السيرفر
function syncLocationsWithServer(csrfToken, userId) {
    openDatabase()
        .then((db) => {
            const transaction = db.transaction('locations', 'readonly');
            const store = transaction.objectStore('locations');
            const request = store.getAll();

            request.onsuccess = () => {
                const locations = request.result;
                if (locations.length === 0) return;

                fetch('/sync-locations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ locations, user_id: userId }),
                })
                    .then((response) => response.json())
                    .then(() => {
                        console.log('Locations synced successfully.');
                        // حذف البيانات بعد المزامنة
                        clearLocationsFromDB();
                    })
                    .catch((error) => {
                        console.error('Error syncing locations:', error.message);
                    });
            };

            request.onerror = (event) => {
                console.error('Error fetching locations from IndexedDB:', event.target.error);
            };
        })
        .catch((error) => console.error(error));
}

// حذف البيانات من IndexedDB
function clearLocationsFromDB() {
    openDatabase()
        .then((db) => {
            const transaction = db.transaction('locations', 'readwrite');
            const store = transaction.objectStore('locations');
            store.clear();

            transaction.oncomplete = () => {
                console.log('All locations cleared from IndexedDB.');
            };

            transaction.onerror = (event) => {
                console.error('Error clearing locations:', event.target.error);
            };
        })
        .catch((error) => console.error(error));
}

self.onmessage = function (event) {
    const data = event.data;

    if (!data || typeof data !== 'object') {
        postMessage({ error: 'Invalid data received in Worker.' });
        return;
    }

    if (data.action === 'sendLocation') {
        const { latitude, longitude, user_id, csrf_token } = data;

        if (latitude && longitude && user_id && csrf_token) {
            storeLocationInDB(latitude, longitude, Date.now());
            updateLocationToServer(latitude, longitude, user_id, csrf_token);
        } else {
            postMessage({ error: 'Incomplete location data received.' });
        }
    } else if (data.action === 'syncLocations') {
        syncLocationsWithServer(data.csrf_token, data.user_id);
    } else if (data.action === 'getCount') {
        getSavedLocationsCount(); // جلب عدد النقاط المحفوظة
    }    else if (data.action === 'stopWorker') {
        stopTracking();
    } else {
        postMessage({ error: 'Unknown action received in Worker.' });
    }
};

// تحديث الموقع على السيرفر
function updateLocationToServer(latitude, longitude, userId, csrfToken) {
    fetch('/update-location', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ latitude, longitude, user_id: userId }),
    })
        .then((response) => response.json())
        .then((data) => {
            postMessage({ success: 'Location updated successfully.', data });
        })
        .catch((error) => {
            postMessage({ error: `Server update error: ${error.message}` });
        });
}


// جلب عدد النقاط المحفوظة
function getSavedLocationsCount() {
    openDatabase()
        .then((db) => {
            const transaction = db.transaction('locations', 'readonly');
            const store = transaction.objectStore('locations');
            const request = store.count();

            request.onsuccess = () => {
                const count = request.result;
                postMessage({ count }); // إرسال العدد إلى الصفحة الرئيسية
                console.log(`عدد النقاط المحفوظة: ${count}`);
            };

            request.onerror = (event) => {
                console.log('Error counting locations:', event.target.error);
            };
        })
        .catch((error) => console.error(error));
}

