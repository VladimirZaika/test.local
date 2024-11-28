document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('#nasa-js-content');
    const preloader = document.querySelector('.preloader');
    const noRequestImage = `${nasaSettings.pluginUrl}images/no-request.png`;
    const placeholderImage = `${nasaSettings.pluginUrl}images/placeholder.png`;

    if (nasaSettings.language_choice === 'js') {
        if (!nasaSettings.api_key) {
            container.innerHTML = `
                <div class="no-images">
                    <img src="${noRequestImage}" alt="No images aviable" />
                </div>
            `;
            preloader.style.display = 'none';
            return;
        }

        const apiUrl = `${nasaSettings.api_url}?api_key=${nasaSettings.api_key}&thumbs=true&start_date=${getStartDate(nasaSettings.image_days)}&end_date=${getEndDate()}`;

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                preloader.style.display = 'none';
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="no-images">
                            <img src="${noRequestImage}" alt="No images aviable" />
                        </div>
                    `;
                } else {
                    data.reverse();

                    data.forEach(image => {
                        const imageItem = `
                            <article class="nasa-image-item">
                                <h2>${image.title || 'No title'}</h2>
                                <img src="${image.url || placeholderImage}" alt="${image.title || 'No image aviable'}"/>
                                <p>${image.explanation || 'Not text'}</p>
                                <p><strong>Date:</strong> ${image.date || 'No date'}</p>
                                <p><strong>Author:</strong> ${image.copyright || 'No author'}</p>
                            </article>
                        `;
                        container.insertAdjacentHTML('beforeend', imageItem);
                    });
                }
            })
            .catch(error => {
                preloader.style.display = 'none';
                console.error('Error fetching NASA images:', error);
                container.innerHTML = `
                    <div class="no-images">
                        <img src="${noRequestImage}" alt="No images aviable" />
                    </div>
                `;
            });
    }

    function getStartDate(days) {
        const date = new Date();
        date.setDate(date.getDate() - days);
        return date.toISOString().split('T')[0];
    }

    function getEndDate() {
        const date = new Date();
        return date.toISOString().split('T')[0];
    }
});
