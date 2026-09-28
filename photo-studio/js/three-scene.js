(() => {
    const container = document.getElementById("three-container");
    if (!container || typeof THREE === "undefined") return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(
        32, container.clientWidth / container.clientHeight, 0.1, 100
    );
    camera.position.set(0, 0.25, 8);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    container.appendChild(renderer.domElement);

    const group = new THREE.Group();
    scene.add(group);

    scene.add(new THREE.AmbientLight(0xffffff, 2.0));
    const key = new THREE.DirectionalLight(0xffffff, 3.0);
    key.position.set(4, 5, 6);
    scene.add(key);

    const rim = new THREE.PointLight(0x8d72ff, 18, 10);
    rim.position.set(-3, 1, 3);
    scene.add(rim);

    // Main floating photo frame
    const frame = new THREE.Group();
    const frameMaterial = new THREE.MeshStandardMaterial({
        color: 0x1b1d27, metalness: 0.65, roughness: 0.22
    });
    const innerMaterial = new THREE.MeshStandardMaterial({
        color: 0xd9dce8, metalness: 0.05, roughness: 0.7
    });

    const outer = new THREE.Mesh(new THREE.BoxGeometry(3.8, 4.6, 0.22), frameMaterial);
    const inner = new THREE.Mesh(new THREE.BoxGeometry(3.25, 4.05, 0.12), innerMaterial);
    inner.position.z = 0.16;

    const photoMat = new THREE.MeshStandardMaterial({
        color: 0x8c75db, roughness: 0.75, metalness: 0.02
    });
    const photo = new THREE.Mesh(new THREE.BoxGeometry(2.92, 3.72, 0.08), photoMat);
    photo.position.z = 0.25;

    frame.add(outer, inner, photo);
    frame.rotation.set(-0.12, -0.32, 0.04);
    group.add(frame);

    // Small floating photo cards
    const smallMat = new THREE.MeshStandardMaterial({ color: 0x303342, roughness: 0.35 });
    for (let i = 0; i < 3; i++) {
        const card = new THREE.Mesh(new THREE.BoxGeometry(.95, 1.2, .08), smallMat);
        card.position.set(
            i === 0 ? -2.2 : i === 1 ? 2.2 : 1.8,
            i === 0 ? 1.5 : i === 1 ? -1.5 : 1.8,
            i === 0 ? -0.5 : -0.2
        );
        card.rotation.z = (i - 1) * 0.22;
        card.rotation.y = (i - 1) * 0.18;
        group.add(card);
    }

    // Particle field
    const points = [];
    for (let i = 0; i < 80; i++) {
        points.push(
            (Math.random() - 0.5) * 7,
            (Math.random() - 0.5) * 6,
            (Math.random() - 0.5) * 3
        );
    }
    const particleGeo = new THREE.BufferGeometry();
    particleGeo.setAttribute("position", new THREE.Float32BufferAttribute(points, 3));
    const particleMat = new THREE.PointsMaterial({
        color: 0xffffff, size: 0.025, transparent: true, opacity: 0.6
    });
    group.add(new THREE.Points(particleGeo, particleMat));

    let targetX = 0, targetY = 0;
    window.addEventListener("mousemove", (e) => {
        targetX = (e.clientX / window.innerWidth - 0.5) * 0.5;
        targetY = (e.clientY / window.innerHeight - 0.5) * 0.3;
    });

    const clock = new THREE.Clock();
    function animate() {
        requestAnimationFrame(animate);
        const t = clock.getElapsedTime();

        frame.rotation.y += (targetX - frame.rotation.y) * 0.015;
        frame.rotation.x += (-0.12 - targetY - frame.rotation.x) * 0.015;
        frame.position.y = Math.sin(t * 0.9) * 0.09;

        group.rotation.y += 0.0009;
        renderer.render(scene, camera);
    }
    animate();

    function resize() {
        const w = container.clientWidth, h = container.clientHeight;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    }
    window.addEventListener("resize", resize);
})();
