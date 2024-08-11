const TWO_PI = Math.PI * 2;
const HALF_PI = Math.PI * 0.5;

// canvas settings
const viewWidth = 512;
const viewHeight = 350;
const drawingCanvas = document.getElementById("drawing_canvas");
let ctx;
const timeStep = 1 / 60;

class Point {
    constructor(x = 0, y = 0) {
        this.x = x;
        this.y = y;
    }
}

class Particle {
    constructor(p0, p1, p2, p3) {
        this.p0 = p0;
        this.p1 = p1;
        this.p2 = p2;
        this.p3 = p3;
        this.time = 0;
        this.duration = 3 + Math.random() * 2;
        this.color = `#${Math.floor(Math.random() * 0xffffff).toString(16)}`;
        this.w = 8;
        this.h = 6;
        this.complete = false;
    }

    update() {
        this.time = Math.min(this.duration, this.time + timeStep);
        const f = Ease.outCubic(this.time, 0, 1, this.duration);
        const p = cubeBezier(this.p0, this.p1, this.p2, this.p3, f);
        const dx = p.x - this.x;
        const dy = p.y - this.y;

        this.r = Math.atan2(dy, dx) + HALF_PI;
        this.sy = Math.sin(Math.PI * f * 10);
        this.x = p.x;
        this.y = p.y;

        this.complete = this.time === this.duration;
    }

    draw() {
        ctx.save();
        ctx.translate(this.x, this.y);
        ctx.rotate(this.r);
        ctx.scale(1, this.sy);
        ctx.fillStyle = this.color;
        ctx.fillRect(-this.w * 0.5, -this.h * 0.5, this.w, this.h);
        ctx.restore();
    }
}

class Loader {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.r = 24;
        this._progress = 0;
        this.complete = false;
    }

    reset() {
        this._progress = 0;
        this.complete = false;
    }

    set progress(p) {
        this._progress = Math.min(Math.max(p, 0), 1);
        this.complete = this._progress === 1;
    }

    get progress() {
        return this._progress;
    }

    draw() {
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.r, -HALF_PI, TWO_PI * this._progress - HALF_PI);
        ctx.lineTo(this.x, this.y);
        ctx.closePath();
        ctx.fill();
    }
}

class Exploader {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.startRadius = 24;
        this.time = 0;
        this.duration = 0.4;
        this.progress = 0;
        this.complete = false;
    }

    reset() {
        this.time = 0;
        this.progress = 0;
        this.complete = false;
    }

    update() {
        this.time = Math.min(this.duration, this.time + timeStep);
        this.progress = Ease.inBack(this.time, 0, 1, this.duration);
        this.complete = this.time === this.duration;
    }

    draw() {
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.startRadius * (1 - this.progress), 0, TWO_PI);
        ctx.fill();
    }
}

let particles = [];
let loader;
let exploader;
let phase = 0;

function initDrawingCanvas() {
    drawingCanvas.width = viewWidth;
    drawingCanvas.height = viewHeight;
    ctx = drawingCanvas.getContext('2d');

    createLoader();
    createExploader();
    createParticles();
}

function createLoader() {
    loader = new Loader(viewWidth * 0.5, viewHeight * 0.5);
}

function createExploader() {
    exploader = new Exploader(viewWidth * 0.5, viewHeight * 0.5);
}

function createParticles() {
    particles = [];
    for (let i = 0; i < 128; i++) {
        const p0 = new Point(viewWidth * 0.5, viewHeight * 0.5);
        const p1 = new Point(Math.random() * viewWidth, Math.random() * viewHeight);
        const p2 = new Point(Math.random() * viewWidth, Math.random() * viewHeight);
        const p3 = new Point(Math.random() * viewWidth, viewHeight + 64);

        particles.push(new Particle(p0, p1, p2, p3));
    }
}

function update() {
    switch (phase) {
        case 0:
            loader.progress += 1 / 45;
            break;
        case 1:
            exploader.update();
            break;
        case 2:
            particles.forEach(p => p.update());
            break;
    }
}

function draw() {
    ctx.clearRect(0, 0, viewWidth, viewHeight);

    switch (phase) {
        case 0:
            loader.draw();
            break;
        case 1:
            exploader.draw();
            break;
        case 2:
            particles.forEach(p => p.draw());
            break;
    }
}

window.onload = () => {
    initDrawingCanvas();
    requestAnimationFrame(loop);
};

function loop() {
    update();
    draw();

    if (phase === 0 && loader.complete) {
        phase = 1;
    } else if (phase === 1 && exploader.complete) {
        phase = 2;
    } else if (phase === 2 && checkParticlesComplete()) {
        phase = 2;
        exploader.reset();
        createParticles();
    }

    requestAnimationFrame(loop);
}

function checkParticlesComplete() {
    return particles.every(p => p.complete);
}

// Ease functions
const Ease = {
    inCubic(t, b, c, d) {
        t /= d;
        return c * t * t * t + b;
    },
    outCubic(t, b, c, d) {
        t /= d;
        t--;
        return c * (t * t * t + 1) + b;
    },
    inOutCubic(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return (c / 2) * t * t * t + b;
        t -= 2;
        return (c / 2) * (t * t * t + 2) + b;
    },
    inBack(t, b, c, d, s = 1.70158) {
        t /= d;
        return c * t * t * ((s + 1) * t - s) + b;
    }
};

function cubeBezier(p0, c0, c1, p1, t) {
    const nt = 1 - t;
    return new Point(
        nt * nt * nt * p0.x + 3 * nt * nt * t * c0.x + 3 * nt * t * t * c1.x + t * t * t * p1.x,
        nt * nt * nt * p0.y + 3 * nt * nt * t * c0.y + 3 * nt * t * t * c1.y + t * t * t * p1.y
    );
}
