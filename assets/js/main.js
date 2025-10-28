(function () {
    const root = document.documentElement;
    const toggle = document.querySelector('.devlog-theme-toggle');

    const setTheme = (mode) => {
        const theme = mode === 'dark' ? 'dark' : 'light';
        root.setAttribute('data-theme', theme);
        if (toggle) {
            toggle.classList.toggle('is-dark', theme === 'dark');
            toggle.setAttribute('aria-pressed', theme === 'dark');
        }
        try {
            window.localStorage.setItem('devlog-theme', theme);
        } catch (error) {
            // Ignore write errors (e.g. private mode).
        }
    };

    const storedTheme = (() => {
        try {
            return window.localStorage.getItem('devlog-theme');
        } catch (error) {
            return null;
        }
    })();

    if (storedTheme) {
        setTheme(storedTheme);
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        setTheme('dark');
    } else {
        setTheme('light');
    }

    if (toggle) {
        toggle.addEventListener('click', () => {
            const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.Prism) {
            if (Prism.plugins && Prism.plugins.autoloader) {
                Prism.plugins.autoloader.languages_path = 'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/';
            }

            document.querySelectorAll('pre code').forEach((code) => {
                const pre = code.closest('pre');
                if (!pre) {
                    return;
                }

                //pre.classList.add('line-numbers');

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'devlog-copy-button';
                button.innerText = 'Copy';

                button.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(code.innerText);
                        button.classList.add('copied');
                        button.innerText = 'Copied!';
                        setTimeout(() => {
                            button.classList.remove('copied');
                            button.innerText = 'Copy';
                        }, 1600);
                    } catch (error) {
                        button.innerText = 'Error';
                    }
                });

                if (!pre.querySelector('.devlog-copy-button')) {
                    pre.appendChild(button);
                }
            });

            document.querySelectorAll('pre code').forEach((code) => {
                if (![...code.classList].some(c => c.startsWith('language-'))) {
                    const text = code.textContent;
                    let lang = 'markup';
                    if (text.includes('<?php')) lang = 'php';
                    else if (text.includes('function') || text.includes('const') || text.includes('let')) lang = 'js';
                    else if (text.includes('<') && text.includes('>')) lang = 'html';
                    code.classList.add(`language-${lang}`);
                }
            });

            Prism.highlightAll();

            document.querySelectorAll('pre code span.token.comment').forEach((comment) => {
                const content = comment.textContent || '';
                if (content.includes('TODO')) {
                    comment.classList.add('devlog-flag', 'devlog-flag--todo');
                }
                if (content.includes('FIX') || content.includes('FIXME')) {
                    comment.classList.add('devlog-flag', 'devlog-flag--fix');
                }
            });
        }
    });
})();
