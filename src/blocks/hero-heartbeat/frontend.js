function HeroPulse(block) {

    const canvas = block.querySelector("canvas");
    const ctx = canvas.getContext("2d");
    const hero = block.querySelector(".hero");

    let w, h, centerY;
    let raf;
    let running = false;

    let waveX = 0;
    let particles = [];
    let triggered = false;

    const CONFIG = {
        waveSpeed: 7,
        triggerOffset: 180,
        color: "#ff2a5f",
        particleSize: 2.4,
        burstStrength: 6,
        gravity: 0.02,
        fadeRate: 0.015
    };

    function resize() {
        w = canvas.width = canvas.offsetWidth;
        h = canvas.height = canvas.offsetHeight;
        centerY = h / 2;
    }

    function waveY(x) {
        const mid = w / 2;
        const d = x - (mid - 150);

        if (d > 0 && d < 300) {
            return centerY +
                Math.sin(d * 0.1) *
                Math.sin(d * 0.02) *
                120;
        }

        return centerY;
    }

    function drawWave() {
        ctx.beginPath();
        ctx.strokeStyle = CONFIG.color;
        ctx.lineWidth = 4;

        for (let i = 0; i < waveX; i += 2) {
            const y = waveY(i);
            if (i === 0) ctx.moveTo(i, y);
            else ctx.lineTo(i, y);
        }

        ctx.stroke();
    }

    function spawnBurst() {
        particles = [];

        for (let x = 0; x < waveX; x += 4) {
            const y = waveY(x);

            const angle = (Math.random() - 0.5) * Math.PI * 1.2;
            const speed = Math.random() * CONFIG.burstStrength + 2;

            particles.push({
                x,
                y,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed,
                life: 1
            });
        }
    }

    function drawParticles() {

        ctx.fillStyle = CONFIG.color;

        for (let i = particles.length - 1; i >= 0; i--) {
            const p = particles[i];

            p.vy += CONFIG.gravity;
            p.x += p.vx;
            p.y += p.vy;
            p.life -= CONFIG.fadeRate;

            if (p.life <= 0) {
                particles.splice(i, 1);
                continue;
            }

            ctx.globalAlpha = p.life;

            ctx.beginPath();
            ctx.arc(p.x, p.y, CONFIG.particleSize, 0, Math.PI * 2);
            ctx.fill();
        }

        ctx.globalAlpha = 1;
    }

    function loop() {
        if (!running) return;

        ctx.clearRect(0, 0, w, h);

        if (!triggered) {

            waveX += CONFIG.waveSpeed;
            drawWave();

            if (waveX > w / 2 + CONFIG.triggerOffset) {
                triggered = true;
                spawnBurst();
            }

        } else {

            drawParticles();

            if (particles.length === 0) stop();
        }

        raf = requestAnimationFrame(loop);
    }

    function start() {
        if (running) return;
        running = true;
        loop();
    }

    function stop() {
        running = false;
        cancelAnimationFrame(raf);
    }

    const observer = new IntersectionObserver(([entry]) => {
        entry.isIntersecting ? start() : stop();
    }, { threshold: 0.2 });

    observer.observe(hero);

    window.addEventListener("resize", resize);
    resize();
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-hero-pulse]").forEach(HeroPulse);
});