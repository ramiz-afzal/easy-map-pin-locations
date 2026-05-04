/**
 * Init maps
 */
window.addEventListener('DOMContentLoaded', function () {
    const maps = document.querySelectorAll('.empl-map');
    if (maps.length == 0 || typeof L === 'undefined') return;

    const POPUP_IMAGE_PRELOAD_MAX_WAIT_MS = 8000;

    const escapeHtml = (value) => {
        const str = value === null || value === undefined ? '' : String(value);
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const preloadPopupImages = async (urls) => {
        const unique = [...new Set(urls.map((u) => String(u).trim()).filter(Boolean))];
        if (unique.length === 0) return;
        await Promise.all(
            unique.map(
                (url) =>
                    new Promise((resolve) => {
                        const img = new Image();
                        const done = () => resolve();
                        img.onload = () => {
                            if (typeof img.decode === 'function') {
                                img.decode().then(done).catch(done);
                            } else {
                                done();
                            }
                        };
                        img.onerror = done;
                        img.src = url;
                    })
            )
        );
    };

    const runMaps = async () => {
        for (let i = 0; i < maps.length; i++) {
            const map = maps[i];
            const locationsData = JSON.parse(map.dataset.locationsData);

            if (!locationsData || locationsData.length == 0) {
                continue;
            }

            // create map locations data
            let mapLocations = [];
            locationsData.forEach(function (item) {
                if (item.latitude && item.longitude) {
                    let pointOnMap = {
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: [item.longitude, item.latitude],
                        },
                        properties: {
                            title: item.title || '',
                            image: item.image || '',
                            url: item.url || '',
                            props: item.props || {},
                        },
                    };
                    mapLocations.push(pointOnMap);
                }
            });

            // init map
            let leafletMap = L.map(map.id);

            // set map properties
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 15,
                attribution: '© OpenStreetMap',
            }).addTo(leafletMap);

            const imageUrls = mapLocations.map((f) => f.properties.image);
            await Promise.race([
                preloadPopupImages(imageUrls),
                new Promise((resolve) => setTimeout(resolve, POPUP_IMAGE_PRELOAD_MAX_WAIT_MS)),
            ]);

            // add data to map
            let geoJSON = L.geoJSON(mapLocations, {
                onEachFeature: function (feature, layer) {
                    const title = escapeHtml(feature?.properties?.title || '');
                    const image = String(feature?.properties?.image || '');
                    const url = String(feature?.properties?.url || '');
                    const featuredImage = image && url ? `<a href="${url}" target="_blank"><img src="${image}" alt="${title}"></a>` : (image ? `<img src="${image}" alt="${title}">` : '');
                    const propContent = feature?.properties?.props ? `<p class="empl-location-props">${Array.from(feature?.properties?.props).map(({ label, value }) => `<strong>${label}:</strong> ${value}`).join('<br/>')}</p>` : ''

                    const popupHtml = `
					<div class="empl-map-popup">
					    <div class="empl-location-img">${featuredImage}</div>
                        ${title ? `<h3 class="empl-location-title">${title}</h3>` : ''}
                        ${propContent ? propContent : ''}
					</div>
				`;

                    layer.bindPopup(popupHtml, {
                        closeButton: true,
                        autoPan: true,
                        autoPanPadding: [48, 48],
                        keepInView: true,
                        maxHeight: 450,
                    });
                },
            }).addTo(leafletMap);

            // set map bounds (padding keeps markers away from viewport edges)
            leafletMap.fitBounds(geoJSON.getBounds(), { padding: [24, 24] });
        }
    };

    void runMaps();
});