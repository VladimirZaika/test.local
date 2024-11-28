(function () {
  'use strict';

  function _arrayLikeToArray(r, a) {
    (null == a || a > r.length) && (a = r.length);
    for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
    return n;
  }
  function _arrayWithHoles(r) {
    if (Array.isArray(r)) return r;
  }
  function _classCallCheck(a, n) {
    if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
  }
  function _defineProperties(e, r) {
    for (var t = 0; t < r.length; t++) {
      var o = r[t];
      o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
    }
  }
  function _createClass(e, r, t) {
    return r && _defineProperties(e.prototype, r), Object.defineProperty(e, "prototype", {
      writable: !1
    }), e;
  }
  function _createForOfIteratorHelper(r, e) {
    var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
    if (!t) {
      if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e) {
        t && (r = t);
        var n = 0,
          F = function () {};
        return {
          s: F,
          n: function () {
            return n >= r.length ? {
              done: !0
            } : {
              done: !1,
              value: r[n++]
            };
          },
          e: function (r) {
            throw r;
          },
          f: F
        };
      }
      throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }
    var o,
      a = !0,
      u = !1;
    return {
      s: function () {
        t = t.call(r);
      },
      n: function () {
        var r = t.next();
        return a = r.done, r;
      },
      e: function (r) {
        u = !0, o = r;
      },
      f: function () {
        try {
          a || null == t.return || t.return();
        } finally {
          if (u) throw o;
        }
      }
    };
  }
  function _iterableToArrayLimit(r, l) {
    var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
    if (null != t) {
      var e,
        n,
        i,
        u,
        a = [],
        f = !0,
        o = !1;
      try {
        if (i = (t = t.call(r)).next, 0 === l) ; else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
      } catch (r) {
        o = !0, n = r;
      } finally {
        try {
          if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return;
        } finally {
          if (o) throw n;
        }
      }
      return a;
    }
  }
  function _nonIterableRest() {
    throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }
  function _slicedToArray(r, e) {
    return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest();
  }
  function _toPrimitive(t, r) {
    if ("object" != typeof t || !t) return t;
    var e = t[Symbol.toPrimitive];
    if (void 0 !== e) {
      var i = e.call(t, r);
      if ("object" != typeof i) return i;
      throw new TypeError("@@toPrimitive must return a primitive value.");
    }
    return (String )(t);
  }
  function _toPropertyKey(t) {
    var i = _toPrimitive(t, "string");
    return "symbol" == typeof i ? i : i + "";
  }
  function _unsupportedIterableToArray(r, a) {
    if (r) {
      if ("string" == typeof r) return _arrayLikeToArray(r, a);
      var t = {}.toString.call(r).slice(8, -1);
      return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0;
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var heroWraps = document.querySelectorAll('.hero-container');
    if (heroWraps.length > 0) {
      var changePosition = function changePosition(wrap) {
        var leftBlock = wrap.querySelector('.left-block-desk');
        var rightBlock = wrap.querySelector('.right-block-desk');
        var leftBlockTitle = leftBlock ? leftBlock.querySelector('.title-wrapper') : null;
        if (leftBlockTitle && rightBlock && rightBlock.dataset.position === 'mob-left') {
          if (window.innerWidth < 768) {
            leftBlockTitle.insertAdjacentElement('afterend', rightBlock);
          } else {
            wrap.appendChild(rightBlock);
          }
        }
      };
      heroWraps.forEach(function (wrap) {
        changePosition(wrap);
        window.addEventListener('resize', function () {
          return changePosition(wrap);
        });
      });
    }
  });

  var MoviesRequest = /*#__PURE__*/function () {
    function MoviesRequest(formId) {
      var _this$section, _this$section2;
      _classCallCheck(this, MoviesRequest);
      this.section = document.querySelector('.section-filters');
      this.cardGrid = (_this$section = this.section) === null || _this$section === void 0 ? void 0 : _this$section.querySelector('.card-grid');
      this.form = document.getElementById(formId);
      this.selectsFrom = (_this$section2 = this.section) === null || _this$section2 === void 0 ? void 0 : _this$section2.querySelectorAll('select');
      this.loadMoreWrap = document.getElementById('load-more-wrapper');
      this.btnApply = this.form.querySelector('.button-submit');
      if (!this.form) {
        console.error("Form with ID ".concat(formId, " not found."));
        return;
      }
      this.requestType = this.form.dataset.request || 'ajax';
      this.pagedInput = this.form.querySelector('input[name="movie_page"]');
    }
    return _createClass(MoviesRequest, [{
      key: "getFormData",
      value: function getFormData() {
        var formData = new FormData(this.form);
        var params = new URLSearchParams(window.location.search);
        var _iterator = _createForOfIteratorHelper(params.entries()),
          _step;
        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var _step$value = _slicedToArray(_step.value, 2),
              key = _step$value[0],
              value = _step$value[1];
            if (!formData.has(key)) {
              formData.append(key, value);
            }
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }
        return new URLSearchParams(formData).toString();
      }
    }, {
      key: "updateURL",
      value: function updateURL() {
        var _this = this;
        var params = new URLSearchParams();
        var fieldNames = ['movie_genre', 'movie_from', 'movie_to'];
        fieldNames.forEach(function (name) {
          var field = _this.form.querySelector("[name=\"".concat(name, "\"]"));
          if (field && field.value.trim() !== '') {
            params.set(name, field.value);
          }
        });
        var newUrl = params.toString() ? "".concat(window.location.pathname, "?").concat(params.toString()) : window.location.pathname;
        window.history.replaceState({}, '', newUrl);
      }
    }, {
      key: "incrementPage",
      value: function incrementPage(resetTo) {
        if (this.pagedInput) {
          var currentPage = parseInt(this.pagedInput.value, 10) || 1;
          this.pagedInput.value = currentPage + 1;
          if (resetTo) {
            this.pagedInput.value = resetTo;
          }
        }
      }
    }, {
      key: "ajax",
      value: function ajax() {
        var method = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'GET';
        var url = "".concat(wpData.ajaxUrl, "?action=get_movies&").concat(this.getFormData());
        return this.fetchData(url, method);
      }
    }, {
      key: "rest",
      value: function rest() {
        var method = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'GET';
        var url = "".concat(wpData.restPath, "get?").concat(this.getFormData());
        return this.fetchData(url, method);
      }
    }, {
      key: "search",
      value: function search(searchTerm) {
        var url = "".concat(wpData.restPath, "get?movie_search=").concat(searchTerm);
        return this.fetchData(url, 'GET');
      }
    }, {
      key: "resetFieldOnSearch",
      value: function resetFieldOnSearch() {
        if (this.selectsFrom) {
          this.selectsFrom.forEach(function (select) {
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
    }, {
      key: "resetState",
      value: function resetState() {
        if (this.selectsFrom) {
          this.selectsFrom.forEach(function (select) {
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
        var newUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, '', newUrl);
      }
    }, {
      key: "fetchData",
      value: function fetchData(url, method) {
        var _this2 = this;
        this.toggleLoading(true);
        return fetch(url, {
          method: method
        }).then(function (response) {
          return response.json();
        }).then(function (data) {
          _this2.toggleLoading(false);
          return data;
        })["catch"](function (error) {
          console.error('Request error:', error);
          _this2.toggleLoading(false);
        });
      }
    }, {
      key: "appendData",
      value: function appendData(markup) {
        if (this.cardGrid) {
          this.cardGrid.insertAdjacentHTML('beforeend', markup);
        }
      }
    }, {
      key: "appendDataSearch",
      value: function appendDataSearch(markup) {
        if (this.cardGrid) {
          this.cardGrid.innerHTML = '';
          this.cardGrid.insertAdjacentHTML('beforeend', markup);
        }
      }
    }, {
      key: "toggleLoading",
      value: function toggleLoading(isLoading) {
        document.body.style.cursor = isLoading ? 'progress' : '';
      }
    }]);
  }();
  document.addEventListener('DOMContentLoaded', function () {
    var sectionFilters = document.querySelector('.section-filters');
    if (sectionFilters) {
      var showResetBtn = function showResetBtn() {
        var formData = new FormData(form);
        var hasValue = formData.get('movie_to').length > 0 || formData.get('movie_from').length > 0 || formData.get('movie_genre').length > 0;
        if (hasValue) {
          btnReset.classList.remove('d-none');
        }
      };
      var loadMoreToggle = function loadMoreToggle() {
        if (loadMoreWrap && inputPaged) {
          if (loadMoreWrap.dataset.maxPages <= inputPaged.value) {
            loadMoreWrap.classList.add('d-none');
          } else {
            loadMoreWrap.classList.remove('d-none');
          }
        }
      };
      var formId = 'movies-filters-form';
      var moviesRequest = new MoviesRequest(formId);
      var form = document.getElementById('movies-filters-form');
      var loadMoreWrap = document.getElementById('load-more-wrapper');
      var inputPaged = document.getElementById('input-hidden-paged');
      var btnReset = document.querySelector('.button-reset');
      var loadMorePreload = loadMoreWrap.querySelector('.button-preloader-wrap');
      var applyPreload = form.querySelector('.btn-wrapper .button-preloader-wrap');
      var searchPreload = form.querySelector('.button-preloader-wrap.button-preloader-wrap-search');
      var urlParams = new URLSearchParams(window.location.search);
      loadMoreToggle();
      if (Array.from(urlParams.entries()).length > 0) {
        btnReset.classList.remove('d-none');
      }
      document.querySelector('.button-submit').addEventListener('click', function (event) {
        event.preventDefault();
        moviesRequest.incrementPage(1);
        applyPreload.classList.add('processing');
        var requestType = moviesRequest.requestType;
        var method = requestType === 'ajax' ? 'ajax' : 'rest';
        moviesRequest[method]().then(function (jsonData) {
          if (jsonData.success) {
            moviesRequest.appendDataSearch(jsonData.data.posts);
            loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
            moviesRequest.updateURL();
          } else {
            moviesRequest.form.reset();
            moviesRequest.resetState();
            var errorMarkupWrap = document.createElement('div');
            errorMarkupWrap.classList.add('movie-error-wrapper');
            var errorMarkup = document.createElement('span');
            errorMarkup.classList.add('movie-error-text');
            errorMarkup.textContent = jsonData.message  ? jsonData.message : 'Films not found';
            errorMarkupWrap.appendChild(errorMarkup);
            form.appendChild(errorMarkupWrap);
            var errors = document.querySelectorAll('.movie-error-wrapper');
            window.setTimeout(function () {
              errors.forEach(function (error) {
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
        var _this3 = this;
        event.preventDefault();
        this.disabled = true;
        loadMorePreload.classList.add('processing');
        moviesRequest.incrementPage();
        var requestType = moviesRequest.requestType;
        var method = requestType === 'ajax' ? 'ajax' : 'rest';
        moviesRequest[method]().then(function (jsonData) {
          if (jsonData.success) {
            moviesRequest.appendData(jsonData.data.posts);
            loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
          }
          _this3.disabled = false;
          loadMorePreload.classList.remove('processing');
          loadMoreToggle();
        });
      });
      document.getElementById('movie_sort').addEventListener('change', function () {
        var requestType = moviesRequest.requestType;
        var method = requestType === 'ajax' ? 'ajax' : 'rest';
        moviesRequest.incrementPage(1);
        moviesRequest[method]().then(function (jsonData) {
          if (jsonData.success) {
            moviesRequest.appendDataSearch(jsonData.data.posts);
            loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
          }
          btnReset.classList.remove('d-none');
          loadMoreToggle();
        });
      });
      var searchInput = document.getElementById('input-search');
      var searchTimeout;
      searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        var searchTerm = searchInput.value.trim();
        if (searchTerm.length > 3) {
          moviesRequest.incrementPage(1);
          moviesRequest.resetFieldOnSearch();
          searchTimeout = setTimeout(function () {
            searchPreload.classList.add('processing');
            moviesRequest.search(searchTerm).then(function (jsonData) {
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
        var _this4 = this;
        event.preventDefault();
        moviesRequest.form.reset();
        moviesRequest.resetState();
        var requestType = moviesRequest.requestType;
        var method = requestType === 'ajax' ? 'ajax' : 'rest';
        moviesRequest[method]().then(function (jsonData) {
          if (jsonData.success) {
            moviesRequest.appendDataSearch(jsonData.data.posts);
            loadMoreWrap.dataset.maxPages = jsonData.data.max_num_pages;
            moviesRequest.updateURL();
          }
          _this4.classList.add('d-none');
        });
      });
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    // Sticky header
    var stickyHeader = /*#__PURE__*/function () {
      function stickyHeader(headerSelector) {
        _classCallCheck(this, stickyHeader);
        this.navbar = document.querySelector(headerSelector);
        this.lastScrollTop = 0;
        this.headerHeight = this.navbar.scrollHeight;
        window.addEventListener('scroll', this.onScroll.bind(this));
        window.addEventListener('load', this.onScroll());
      }
      return _createClass(stickyHeader, [{
        key: "onScroll",
        value: function onScroll() {
          var scroll = window.scrollY || document.documentElement.scrollTop;
          if (scroll > this.lastScrollTop) {
            this.navbar.classList.add('scrolled-down');
            this.navbar.classList.remove('scrolled-up');
          } else if (scroll === 0) {
            this.navbar.classList.remove('scrolled-down');
            this.navbar.classList.remove('scrolled-up');
            this.lastScrollTop = 0;
          } else if (scroll < this.lastScrollTop && scroll > 100) {
            this.navbar.classList.remove('scrolled-down');
            this.navbar.classList.add('scrolled-up');
          }
          this.lastScrollTop = scroll;
        }
      }]);
    }();
    if (document.querySelector('header')) {
      new stickyHeader('.header');
    }

    //mobile menu
    var bodyLockStatus = true;
    var bodyLockToggle = function bodyLockToggle() {
      var delay = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 500;
      if (document.documentElement.classList.contains('lock')) {
        bodyUnlock(delay);
      } else {
        bodyLock(delay);
      }
    };
    var bodyUnlock = function bodyUnlock() {
      var delay = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 500;
      var body = document.querySelector("body");
      var stickyHeader = document.querySelector("header._header-scroll");
      if (bodyLockStatus) {
        var lock_padding = document.querySelectorAll("[data-lp]");
        setTimeout(function () {
          for (var index = 0; index < lock_padding.length; index++) {
            var el = lock_padding[index];
            el.style.paddingRight = '0px';
          }
          body.style.paddingRight = '0px';
          if (stickyHeader) {
            stickyHeader.style.right = '0px';
          }
          document.documentElement.classList.remove("lock");
        }, delay);
        bodyLockStatus = false;
        setTimeout(function () {
          bodyLockStatus = true;
        }, delay);
      }
    };
    var bodyLock = function bodyLock() {
      var delay = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 500;
      var body = document.querySelector("body");
      var stickyHeader = document.querySelector("header._header-scroll");
      if (bodyLockStatus) {
        var lock_padding = document.querySelectorAll("[data-lp]");
        for (var index = 0; index < lock_padding.length; index++) {
          var el = lock_padding[index];
          el.style.paddingRight = window.innerWidth - document.documentElement.scrollWidth + 'px';
        }
        body.style.paddingRight = window.innerWidth - document.documentElement.scrollWidth + 'px';
        if (stickyHeader) {
          stickyHeader.style.right = (window.innerWidth - document.documentElement.scrollWidth) / 2 + 'px';
        }
        document.documentElement.classList.add("lock");
        bodyLockStatus = false;
        setTimeout(function () {
          bodyLockStatus = true;
        }, delay);
      }
    };
    (function menuInit() {
      if (document.querySelector(".icon-menu")) {
        document.addEventListener("click", function (e) {
          if (bodyLockStatus && e.target.closest('.icon-menu')) {
            bodyLockToggle();
            document.documentElement.classList.toggle("menu-open");
          }
        });
      }
    })();
  });

})();
