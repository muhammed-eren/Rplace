<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern R/place</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --accent-color: #9b59b6;
            --dark-bg: #121212;
            --dark-surface: #1e1e1e;
            --light-text: #f5f5f5;
            --border-radius: 12px;
            --shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--dark-bg);
            color: var(--light-text);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: var(--dark-surface);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .app-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 0 0 10px rgba(52, 152, 219, 0.5);
        }

        .app-subtitle {
            font-size: 0.9rem;
            opacity: 0.7;
            margin-left: 5px;
        }

        .app-info {
            position: absolute;
            top: 15px;
            right: 20px;
            background-color: var(--dark-surface);
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            box-shadow: var(--shadow);
            gap: 10px;
            z-index: 1000;
        }

        .users-online {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
        }

        .users-online i {
            color: #2ecc71;
        }

        .zoom-container {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .zoom-content {
            position: absolute;
            transform-origin: center;
            transform: scale(1);
            cursor: grab;
        }

        .zoom-content:active {
            cursor: grabbing;
        }

        .canvas-grid {
            border-spacing: 1px;
            border-collapse: separate;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 2px;
            overflow: hidden;
        }

        .pixel {
            width: 12px;
            height: 12px;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--dark-bg);
            position: relative;
        }

        .pixel:hover {
            transform: scale(1.3);
            z-index: 10;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
        }

        .color-palette {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--dark-surface);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 15px;
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 8px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .color-option {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .color-option:hover {
            transform: scale(1.15);
        }

        .color-option.selected {
            border: 2px solid white;
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .zoom-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }

        .zoom-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--dark-surface);
            color: var(--light-text);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: all 0.2s ease;
        }

        .zoom-btn:hover {
            background-color: var(--primary-color);
            transform: scale(1.1);
        }

        .coordinates {
            position: fixed;
            left: 20px;
            bottom: 20px;
            background-color: var(--dark-surface);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: var(--shadow);
            z-index: 1000;
        }

        .cooldown {
            position: fixed;
            top: 80px;
            right: 20px;
            background-color: var(--dark-surface);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            display: none;
            box-shadow: var(--shadow);
            z-index: 1000;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(155, 89, 182, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(155, 89, 182, 0); }
            100% { box-shadow: 0 0 0 0 rgba(155, 89, 182, 0); }
        }

        /* Toast notification for pixel placement */
        .toast {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(46, 204, 113, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: var(--shadow);
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .toast.show {
            opacity: 1;
        }

        /* Dark mode toggle */
        .theme-toggle {
            margin-left: 20px;
            background: none;
            border: none;
            color: var(--light-text);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            color: var(--primary-color);
            transform: rotate(30deg);
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .color-palette {
                grid-template-columns: repeat(8, 1fr);
                max-width: 95vw;
            }
            
            .color-option {
                width: 30px;
                height: 30px;
            }
            
            .app-info {
                top: auto;
                bottom: 80px;
                right: 20px;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div class="app-header">
        <div>
            <h1 class="app-title">Legande <span class="app-subtitle">Ortak Sanat</span></h1>
        </div>
        <!-- <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button> -->
    </div>

    <div class="app-info">
        <div class="users-online">
            <i class="fas fa-circle"></i>
            <span id="onlineCount">124</span> online
        </div>
    </div>

    <div class="zoom-container">
        <div class="zoom-content" id="zoomMe">
            <table class="canvas-grid">
                <?php
                    $x = 100;
                    $y = 60;
                    for ($i = 0; $i < $y; $i++) {
                        echo "<tr>";
                        for ($j = 0; $j < $x; $j++) {
                            echo "<td class='pixel' data-x='{$j}' data-y='{$i}'></td>";
                        }
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
    </div>

    <div class="color-palette" id="colorPalette">
        <div class="color-option selected" style="background: #FF4500" data-color="#FF4500"></div>
        <div class="color-option" style="background: #FFA800" data-color="#FFA800"></div>
        <div class="color-option" style="background: #FFD635" data-color="#FFD635"></div>
        <div class="color-option" style="background: #00A368" data-color="#00A368"></div>
        <div class="color-option" style="background: #7EED56" data-color="#7EED56"></div>
        <div class="color-option" style="background: #2450A4" data-color="#2450A4"></div>
        <div class="color-option" style="background: #3690EA" data-color="#3690EA"></div>
        <div class="color-option" style="background: #51E9F4" data-color="#51E9F4"></div>
        <div class="color-option" style="background: #811E9F" data-color="#811E9F"></div>
        <div class="color-option" style="background: #FF99AA" data-color="#FF99AA"></div>
        <div class="color-option" style="background: #9C6926" data-color="#9C6926"></div>
        <div class="color-option" style="background: #000000" data-color="#000000"></div>
        <div class="color-option" style="background: #232322" data-color="#232322"></div>
        <div class="color-option" style="background: #D4D7D9" data-color="#D4D7D9"></div>
        <div class="color-option" style="background: #FFFFFF" data-color="#FFFFFF"></div>
        <div class="color-option" style="background: #FF3881" data-color="#FF3881"></div>
    </div>

    <div class="zoom-controls">
        <button class="zoom-btn" id="zoomIn"><i class="fas fa-plus"></i></button>
        <button class="zoom-btn" id="zoomOut"><i class="fas fa-minus"></i></button>
        <button class="zoom-btn" id="resetZoom"><i class="fas fa-home"></i></button>
    </div>

    <div class="coordinates" id="coordinates">X: 0, Y: 0</div>
    <div class="cooldown" id="cooldown">Bekleme: <span id="cooldownTime">2s</span></div>
    <div class="toast" id="toast">Piksel başarıyla yerleştirildi!</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            let selectedColor = "#FF4500";
            let currentX = 0;
            let currentY = 0;
            let scale = 1;
            let zoomSpeed = 0.1;
            let maxScale = 5;
            let minScale = 0.5;
            let isDragging = false;
            let startX, startY, scrollLeft, scrollTop;
            const cooldownTime = 2; // seconds
            let cooldownActive = false;
            let onCooldown = false;

            // Elements
            const zoomContent = document.getElementById('zoomMe');
            const colorOptions = document.querySelectorAll('.color-option');
            const coordinates = document.getElementById('coordinates');
            const cooldownEl = document.getElementById('cooldown');
            const cooldownTimeEl = document.getElementById('cooldownTime');
            const toast = document.getElementById('toast');
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            
            // Color selection
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelector('.color-option.selected').classList.remove('selected');
                    this.classList.add('selected');
                    selectedColor = this.getAttribute('data-color');
                });
            });

            // Pixel placement
            document.querySelector('.canvas-grid').addEventListener("click", function(event) {
                if (event.target.classList.contains('pixel') && !onCooldown) {
                    const x = event.target.dataset.x;
                    const y = event.target.dataset.y;
                    
                    event.target.style.backgroundColor = selectedColor;
                    
                    // Show toast
                    showToast(`Pixel placed at (${x}, ${y})`);
                    
                    // Set cooldown
                    activateCooldown();
                    
                    // Send to server
                    $.ajax({
                        url: 'setCanvas.php',
                        type: 'POST',
                        data: { x: x, y: y, color: selectedColor },
                        success: function() {
                            // Piksel yerleştirme başarılı olduğunda online durumu güncelle
                            updateOnlineUsers();
                        }
                    });
                } else if (onCooldown && event.target.classList.contains('pixel')) {
                    cooldownEl.style.display = 'block';
                    clearTimeout(cooldownActive);
                    cooldownActive = setTimeout(() => {
                        cooldownEl.style.display = 'none';
                    }, 2000);
                }
            });

            // Show coordinates on hover
            document.querySelector('.canvas-grid').addEventListener("mousemove", function(event) {
                if (event.target.classList.contains('pixel')) {
                    currentX = event.target.dataset.x;
                    currentY = event.target.dataset.y;
                    coordinates.textContent = `X: ${currentX}, Y: ${currentY}`;
                }
            });

            // Zoom controls
            document.getElementById('zoomIn').addEventListener('click', () => {
                updateZoom(scale + zoomSpeed);
            });
            
            document.getElementById('zoomOut').addEventListener('click', () => {
                updateZoom(scale - zoomSpeed);
            });
            
            document.getElementById('resetZoom').addEventListener('click', () => {
                updateZoom(1);
                zoomContent.style.transform = `scale(1)`;
                zoomContent.style.transformOrigin = 'center center';
            });

            // Mousewheel zoom
            document.addEventListener("wheel", (event) => {
                event.preventDefault();
                const zoomDirection = event.deltaY > 0 ? -zoomSpeed : zoomSpeed;
                updateZoom(scale + zoomDirection);
                
                const rect = zoomContent.getBoundingClientRect();
                const mouseX = event.clientX - rect.left;
                const mouseY = event.clientY - rect.top;
                
                zoomContent.style.transformOrigin = `${mouseX}px ${mouseY}px`;
            });

            // Drag functionality
            zoomContent.addEventListener('mousedown', (e) => {
                isDragging = true;
                zoomContent.style.cursor = 'grabbing';
                startX = e.clientX;
                startY = e.clientY;
                
                const transform = zoomContent.style.transform;
                const translateMatch = transform.match(/translate\(([^,]+)px, ([^)]+)px\)/);
                
                if (translateMatch) {
                    scrollLeft = parseInt(translateMatch[1]);
                    scrollTop = parseInt(translateMatch[2]);
                } else {
                    scrollLeft = 0;
                    scrollTop = 0;
                }
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                
                const x = e.clientX;
                const y = e.clientY;
                const moveX = (x - startX) / scale;
                const moveY = (y - startY) / scale;
                
                const newX = scrollLeft + moveX;
                const newY = scrollTop + moveY;
                
                const scaleStr = `scale(${scale})`;
                const translateStr = `translate(${newX}px, ${newY}px)`;
                
                zoomContent.style.transform = `${translateStr} ${scaleStr}`;
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
                zoomContent.style.cursor = 'grab';
            });

            // Function to update zoom
            function updateZoom(newScale) {
                scale = Math.max(minScale, Math.min(maxScale, newScale));
                
                // Get current transform
                const transform = zoomContent.style.transform;
                const translateMatch = transform.match(/translate\(([^,]+)px, ([^)]+)px\)/);
                
                let translateStr = '';
                if (translateMatch) {
                    translateStr = `translate(${translateMatch[1]}px, ${translateMatch[2]}px)`;
                }
                
                zoomContent.style.transform = `${translateStr} scale(${scale})`;
            }

            // Function to show toast
            function showToast(message) {
                toast.textContent = message;
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 2000);
            }

            // Function to activate cooldown
            function activateCooldown() {
                onCooldown = true;
                cooldownEl.style.display = 'block';
                
                let timeLeft = cooldownTime;
                cooldownTimeEl.textContent = `${timeLeft}s`;
                
                const interval = setInterval(() => {
                    timeLeft--;
                    cooldownTimeEl.textContent = `${timeLeft}s`;
                    
                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        onCooldown = false;
                        cooldownEl.style.display = 'none';
                    }
                }, 1000);
            }

            // Theme toggle
            // themeToggle.addEventListener('click', () => {
            //     body.classList.toggle('light-mode');
            //     const icon = themeToggle.querySelector('i');
            //     if (icon.classList.contains('fa-moon')) {
            //         icon.classList.replace('fa-moon', 'fa-sun');
            //     } else {
            //         icon.classList.replace('fa-sun', 'fa-moon');
            //     }
            // });

            // Get canvas data from server

            // Online kullanıcı sayısını güncelleyen fonksiyon
            function updateOnlineUsers() {
                $.ajax({
                    url: 'online_users.php',
                    type: 'POST',
                    data: { action: 'ping' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.count) {
                            document.getElementById('onlineCount').textContent = response.count;
                        }
                    }
                });
            }

            // İlk kontrol
            updateOnlineUsers();

            // Her 15 saniyede bir güncelle
            setInterval(updateOnlineUsers, 15000);
            
            function getCanvas() {
                $.ajax({
                    url: 'getCanvas.php',
                    type: 'POST',
                    success: function(response) {
                        var data = JSON.parse(response);
                        data.forEach(item => {
                            const cell = document.querySelector(`.pixel[data-x="${item.x}"][data-y="${item.y}"]`);
                            if (cell) {
                                cell.style.backgroundColor = item.color;
                            }
                        });
                    }
                });
            }

            // Simulate online count changing slightly
            // setInterval(() => {
            //     const onlineCount = document.getElementById('onlineCount');
            //     const currentCount = parseInt(onlineCount.textContent);
            //     const change = Math.random() > 0.5 ? 1 : -1;
            //     onlineCount.textContent = currentCount + change;
            // }, 5000);

            // Initial canvas load and polling
            getCanvas();
            setInterval(function() {
                getCanvas();
            }, 1000);
        });
    </script>
</body>
</html>