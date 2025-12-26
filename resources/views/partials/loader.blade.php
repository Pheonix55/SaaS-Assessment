<style>
    .loader-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999999;
    }

    .loader.show {
        display: block;
    }

    .loader {
        display: none;
        color: var(--theme-color);
        font-size: 10px;
        width: 1em;
        height: 1em;
        border-radius: 50%;
        position: relative;
        text-indent: -9999em;
        animation: mulShdSpin 1.3s infinite linear;
        transform: translateZ(0);
    }

    @keyframes mulShdSpin {

        0%,
        100% {
            box-shadow: 0 -3em 0 0.2em,
                2em -2em 0 0em, 3em 0 0 -1em,
                2em 2em 0 -1em, 0 3em 0 -1em,
                -2em 2em 0 -1em, -3em 0 0 -1em,
                -2em -2em 0 0;
        }

        12.5% {
            box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em,
                3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em,
                -2em 2em 0 -1em, -3em 0 0 -1em,
                -2em -2em 0 -1em;
        }

        25% {
            box-shadow: 0 -3em 0 -0.5em,
                2em -2em 0 0, 3em 0 0 0.2em,
                2em 2em 0 0, 0 3em 0 -1em,
                -2em 2em 0 -1em, -3em 0 0 -1em,
                -2em -2em 0 -1em;
        }

        37.5% {
            box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em,
                3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em,
                -2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
        }

        50% {
            box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em,
                3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em,
                -2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
        }

        62.5% {
            box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em,
                3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0,
                -2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
        }

        75% {
            box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em,
                3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
                -2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
        }

        87.5% {
            box-shadow: 0em -3em 0 0, 2em -2em 0 -1em,
                3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
                -2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
        }
    }
</style>
<div class="loader-container">
    <span class="loader" id="loader_anim"></span>
</div>
<script>
    const Loader = (function() {
        const loader = document.getElementById('loader_anim');

        if (!loader) {
            console.error('Loader element #loader_anim not found');
        }

        return {
            show() {
                loader?.classList.add('show');
            },

            hide() {
                loader?.classList.remove('show');
            }
        };
    })();
</script>
