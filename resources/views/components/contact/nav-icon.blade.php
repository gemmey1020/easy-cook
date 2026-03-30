<div class="popup">
    <div class="navigation active">
        <img src="/assets/img/logo.png" alt="menu icon" class="menu-icon">
        <a href="tel:+201556002920" class="track-brnc" data-type="call">
        <span style="--i:4;--x:1;--y:1;">
            <i class="fa-solid fa-phone"></i>
        </span>
        </a>

        <span style="--i:6;--x:0;--y:0;" class="x-mark">
            <img src="/assets/img/logo.png" alt="close menu" class="close-icon">
        </span>
        <a href="https://wa.me/201556002920" class="track-brnc" data-type="whatsapp" target="_blank">
            <span style="--i:8;--x:1;--y:-1;">
                <i class="fa-brands fa-whatsapp" style="font-size: 1.8rem !important;"></i>
            </span>
        </a>
    </div>
</div>
<style>
    .popup {
        position: fixed;
        bottom: 50%;
        left: -8px;
        /*background: red;*/
        z-index: 999 !important
    }

    .navigation {
        position: relative;
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, rgba(37, 138, 67, 0.6), rgba(111, 179, 94, 0.6));
        backdrop-filter: blur(10px);
        border-radius: 10px;
        cursor: pointer;
        transition: .5s;
        transition-delay: .8s;
    }


    .menu-icon {

        position: absolute;
        top: 7px;
        width: 90px;
        /*height: 90px;*/
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
        transform: scale(1) rotate(90deg);
    }


    .navigation.active .menu-icon {
        opacity: 0;
        animation-play-state: paused;
    }


    @keyframes scaleAndRotate {
        0% {
            transform: scale(1) rotate(0deg);
        }
        25% {
            transform: scale(1.15) rotate(0deg);
        }
        50% {
            transform: scale(1.15) rotate(90deg);
        }
        75% {
            transform: scale(1) rotate(90deg);
        }
        100% {
            transform: scale(1) rotate(0deg);
        }
    }


    .navigation span {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        transition: transform .5s, width .5s, height .5s, background .5s, opacity .5s;
        transition-delay: calc(.1s * var(--i));
    }


    .navigation.active span {
        width: 100%;
        height: 100%;
        opacity: 1;
        background: rgba(37, 138, 67, 0.81);
        transform: translate(calc(50px * var(--x)), calc(50px * var(--y)));
    }

    .x-mark {
        background: none !important;

    }
    .x-mark i{
        display: none;

    }
    .close-icon {
        width:60px;
        height: 60px;
        object-fit: contain;
        /* absolutely center it: */
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        transition: transform .3s;
    }



    .navigation span i {
        transition: .5s;
        font-size: 0em;
    }

    .navigation.active span i {
        font-size: 1.35em;
        color: #fff;
    }

    .navigation.active span:hover i {
        color: #2dfc52;
        filter: drop-shadow(0 0 2px #2dfc52) drop-shadow(0 0 5px #2dfc52) drop-shadow(0 0 15px #2dfc52);
    }
</style>
<!-- ========= Sctipt ======== -->
<script>
    const navigation = document.querySelector(".navigation");

    navigation.addEventListener("click", () => {
        navigation.classList.toggle("active");
    })
</script>
<script>
    document.querySelectorAll('.track-brnc').forEach(el => {
        el.addEventListener('click', function (e) {
            fetch('/api/track-click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    type: this.dataset.type,
                    url: this.href
                })
            });
        });
    });
</script>
