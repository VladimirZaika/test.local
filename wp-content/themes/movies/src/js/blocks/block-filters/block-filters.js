class MoviesRequest {
    constructor(formId) {
        this.section = document.querySelector('.section-filters');
        this.cardGrid = this.section?.querySelector('.card-grid');
        this.form = document.getElementById(formId);
        this.selectsFrom = this.section?.querySelectorAll('select');
        this.loadMoreWrap = document.getElementById('load-more-wrapper');
        this.btnApply = this.form.querySelector('.button-submit');

        if (!this.form) {
            console.error(`Form with ID ${formId} not found.`);
            return;
        }

        this.requestType = this.form.dataset.request || 'ajax';
        this.pagedInput = this.form.querySelector('input[name="movie_page"]');
    }

    getFormData() {
        const formData = new FormData(this.form);
        const params = new URLSearchParams(window.location.search);

        for (const [key, value] of params.entries()) {
            if (!formData.has(key)) {
                formData.append(key, value);
            }
        }

        return new URLSearchParams(formData).toString();
    }

    updateURL() {
        const params = new URLSearchParams();
        const fieldNames = ['movie_genre', 'movie_from', 'movie_to'];
    
        fieldNames.forEach(name => {
            const field = this.form.querySelector(`[name="${name}"]`);
    
            if (field && field.value.trim() !== '') {
                params.set(name, field.value);
            }
        });
    
        const newUrl = params.toString()
            ? `${window.location.pathname}?${params.toString()}`
            : window.location.pathname;
    
        window.history.replaceState({}, '', newUrl);
    }

    incrementPage(resetTo) {
        if (this.pagedInput) {
            const currentPage = parseInt(this.pagedInput.value, 10) || 1;
            this.pagedInput.value = currentPage + 1;

            if (resetTo) {
                this.pagedInput.value = resetTo;
            }
        }
    }

    ajax(method = 'GET') {
        const url = `${wpData.ajaxUrl}?action=get_movies&${this.getFormData()}`;

        return this.fetchData(url, method);
    }

    rest(method = 'GET') {
        const url = `${wpData.restPath}get?${this.getFormData()}`;

        return this.fetchData(url, method);
    }

    search(searchTerm) {
        const url = `${wpData.restPath}get?movie_search=${searchTerm}`;

        return this.fetchData(url, 'GET');
    }

    resetFieldOnSearch() {
        if (this.selectsFrom) {
            this.selectsFrom.forEach(select => {
                select.value = '';
                select.disabled = true;
            });
        }

        if (this.loadMoreWrap) {
            this.loadMoreWrap.classList.add('d-none');
        }

        if (this.btnApply) {
            this.btnApply.disabled = true;
        }

        if (this.pagedInput) {
            this.pagedInput.value = 1;
        }
    }

    resetState() {
        if (this.selectsFrom) {
            this.selectsFrom.forEach(select => {
                select.value = '';
                select.disabled = false;
            });
        }

        if (this.loadMoreWrap) {
            this.loadMoreWrap.classList.remove('d-none');
        }

        if (this.btnApply) {
            this.btnApply.disabled = false;
        }

        if (this.pagedInput) {
            this.pagedInput.value = 1;
        }

        const newUrl = window.location.origin + window.location.pathname;

        window.history.replaceState({}, '', newUrl);
    }

    fetchData(url, method) {
        this.toggleLoading(true);

        return fetch(url, { method })
            .then(response => response.json())
            .then(data => {
                this.toggleLoading(false);
                return data;
            })
            .catch(error => {
                console.error('Request error:', error);
                this.toggleLoading(false);
            });
    }

    appendData(markup) {
        if (this.cardGrid) {
            this.cardGrid.insertAdjacentHTML('beforeend', markup);
        }
    }

    appendDataSearch(markup) {
        if (this.cardGrid) {
            this.cardGrid.innerHTML = '';
            this.cardGrid.insertAdjacentHTML('beforeend', markup);
        }
    }

    toggleLoading(isLoading) {
        document.body.style.cursor = isLoading ? 'progress' : '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const sectionFilters = document.querySelector('.section-filters');

    if (sectionFilters) {
        const formId = 'movies-filters-form';
        const moviesRequest = new MoviesRequest(formId);
        const form = document.getElementById('movies-filters-form');
        const loadMoreWrap = document.getElementById('load-more-wrapper');
        const inputPaged = document.getElementById('input-hidden-paged');
        const btnReset = document.querySelector('.button-reset');
        const loadMorePreload = loadMoreWrap.querySelector('.button-preloader-wrap');
        const applyPreload = form.querySelector('.btn-wrapper .button-preloader-wrap');
        const searchPreload = form.querySelector('.button-preloader-wrap.button-preloader-wrap-search');
        const urlParams = new URLSearchParams(window.location.search);
        
        function showResetBtn() {
            const formData = new FormData(form);
            const hasValue = (formData.get('movie_to').length > 0) || (formData.get('movie_from').length > 0) || (formData.get('movie_genre').length > 0);
            
            if (hasValue) {
                btnReset.classList.remove('d-none');
            }
        };

        function loadMoreToggle() {
            if ((loadMoreWrap && inputPaged)) {
                if ((loadMoreWrap.dataset.maxPages <= inputPaged.value)) {
                    loadMoreWrap.classList.add('d-none');
                } else {
                    loadMoreWrap.classList.remove('d-none');
                }
            }
        };

        loadMoreToggle();

        if (Array.from(urlParams.entries()).length > 0) {
            btnReset.classList.remove('d-none');
        }

        document.querySelector('.button-submit').addEventListener('click', event => {
            event.preventDefault();

            moviesRequest.incrementPage(1);

            applyPreload.classList.add('processing');
            const requestType = moviesRequest.requestType;
            const method = requestType === 'ajax' ? 'ajax' : 'rest';

            moviesRequest[method]().then(jsonData => {
                if (jsonData.success) {
                    moviesRequest.appendDataSearch(jsonData.data.posts);
                    loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;

                    moviesRequest.updateURL();
                } else {
                    moviesRequest.form.reset();
                    moviesRequest.resetState();

                    const errorMarkupWrap = document.createElement('div');
                    errorMarkupWrap.classList.add('movie-error-wrapper');

                    const errorMarkup = document.createElement('span');
                    errorMarkup.classList.add('movie-error-text');
                    errorMarkup.textContent = jsonData.message ? jsonData.message : 'Films not found';

                    errorMarkupWrap.appendChild(errorMarkup);
                    form.appendChild(errorMarkupWrap);

                    const errors = document.querySelectorAll('.movie-error-wrapper');

                    window.setTimeout(() => {
                        errors.forEach(error => {
                            error.remove();
                        });
                    }, 3000);
                }

                applyPreload.classList.remove('processing');

                showResetBtn();
                loadMoreToggle();
            });
        });

        document.querySelector('.button-load-more').addEventListener('click', function (event) {
            event.preventDefault();

            this.disabled = true;

            loadMorePreload.classList.add('processing');

            moviesRequest.incrementPage();

            const requestType = moviesRequest.requestType;
            const method = requestType === 'ajax' ? 'ajax' : 'rest';

            moviesRequest[method]().then(jsonData => {
                if (jsonData.success) {
                    moviesRequest.appendData(jsonData.data.posts);
                    loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
                }

                this.disabled = false;

                loadMorePreload.classList.remove('processing');

                loadMoreToggle();
            });
        });

        document.getElementById('movie_sort').addEventListener('change', () => {
            const requestType = moviesRequest.requestType;
            const method = requestType === 'ajax' ? 'ajax' : 'rest';

            moviesRequest.incrementPage(1);

            moviesRequest[method]().then(jsonData => {
                if (jsonData.success) {
                    moviesRequest.appendDataSearch(jsonData.data.posts);
                    loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
                }

                btnReset.classList.remove('d-none');

                loadMoreToggle();
            });
        });

        const searchInput = document.getElementById('input-search');
        let searchTimeout;

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);

            const searchTerm = searchInput.value.trim();

            if (searchTerm.length > 3) {
                moviesRequest.incrementPage(1);
                moviesRequest.resetFieldOnSearch();

                searchTimeout = setTimeout(() => {
                    searchPreload.classList.add('processing');

                    moviesRequest.search(searchTerm).then(jsonData => {
                        if (jsonData.success) {
                            moviesRequest.appendDataSearch(jsonData.data.posts);
                            loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
                        }

                        btnReset.classList.remove('d-none');

                        searchPreload.classList.remove('processing');
                    });
                }, 500);
            }
        });

        btnReset.addEventListener('click', function (event) {
            event.preventDefault();

            moviesRequest.form.reset();

            moviesRequest.resetState();

            const requestType = moviesRequest.requestType;
            const method = requestType === 'ajax' ? 'ajax' : 'rest';
            
            moviesRequest[method]().then(jsonData => {
                if (jsonData.success) {
                    moviesRequest.appendDataSearch(jsonData.data.posts);
                    loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;

                    moviesRequest.updateURL();
                }

                this.classList.add('d-none');
            });
        });
    }
});