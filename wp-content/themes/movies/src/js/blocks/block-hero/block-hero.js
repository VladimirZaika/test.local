document.addEventListener('DOMContentLoaded', () => {
    const heroWraps = document.querySelectorAll('.hero-container');

    if (heroWraps.length > 0) {
        function changePosition(wrap) {
            const leftBlock = wrap.querySelector('.left-block-desk');
            const rightBlock = wrap.querySelector('.right-block-desk');
            const leftBlockTitle = leftBlock ? leftBlock.querySelector('.title-wrapper') : null;

            if (leftBlockTitle && rightBlock && rightBlock.dataset.position === 'mob-left') {
                if (window.innerWidth < 768) {
                    leftBlockTitle.insertAdjacentElement('afterend', rightBlock);
                } else {
                    wrap.appendChild(rightBlock);
                }
            }
        }

        heroWraps.forEach(wrap => {
            changePosition(wrap);
            window.addEventListener('resize', () => changePosition(wrap));
        });
    }
});
