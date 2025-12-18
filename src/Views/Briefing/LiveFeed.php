<?php include __DIR__ . '/../Layout/Header.php'; ?>

<main class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3rem;">
        <div>
            <h1 class="serif-headline" style="font-size: 3rem; margin: 0;">Live Briefings</h1>
            <p style="font-family: var(--font-sans); color: #666; margin: 0.5rem 0 0 0;">Moment-by-moment updates on developing stories.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; background: #fee2e2; color: #dc2626; padding: 0.5rem 1rem; border-radius: 9999px; font-weight: 800; font-size: 0.9rem; letter-spacing: 1px;">
            <span class="pulse-dot"></span> LIVE UPDATES
        </div>
    </div>

    <div class="live-timeline" id="live-timeline" style="border-left: 2px solid #eee; padding-left: 2rem; margin-left: 0.5rem;">
        <?php foreach ($liveNews as $item): ?>
            <div class="timeline-item" data-id="<?= md5($item['url']) ?>" style="position: relative; margin-bottom: 4rem;">
                <!-- Timeline Dot -->
                <div style="position: absolute; left: calc(-2rem - 6px); top: 0; width: 10px; height: 10px; background: #dc2626; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 0 2px #dc2626;"></div>
                
                <div class="time-meta" style="font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; color: #666; margin-bottom: 0.5rem;">
                    <?= date('H:i', strtotime($item['publishedAt'])) ?> &bull; <?= strtoupper(parse_url($item['url'], PHP_URL_HOST)) ?>
                </div>

                <h3 class="serif-headline" style="font-size: 1.6rem; margin: 0 0 1rem 0; line-height: 1.2;">
                    <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank" style="text-decoration: none; color: inherit;">
                        <?= htmlspecialchars($item['title']) ?>
                    </a>
                </h3>

                <?php if (!empty($item['urlToImage'])): ?>
                    <img src="<?= htmlspecialchars($item['urlToImage']) ?>" style="width: 100%; height: 300px; object-fit: cover; border-radius: 4px; margin-bottom: 1rem;">
                <?php endif; ?>

                <p style="font-family: var(--font-sans); line-height: 1.6; color: #444; margin: 0;">
                    <?= htmlspecialchars($item['description'] ?? '') ?>
                </p>
                
                <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank" style="display: inline-block; margin-top: 1rem; font-family: var(--font-sans); font-weight: bold; color: #000; font-size: 0.85rem; text-decoration: underline;">
                    View coverage
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<style>
    .pulse-dot {
        width: 10px;
        height: 10px;
        background-color: #dc2626;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    .timeline-item { transition: all 0.5s ease; }
    .timeline-item:hover h3 { text-decoration: underline; }
    
    .new-item-highlight {
        background: #fffbef;
        animation: highlightFade 3s forwards;
    }
    
    @keyframes highlightFade {
        from { background: #fff9c4; transform: scale(1.02); }
        to { background: transparent; transform: scale(1); }
    }
</style>

<script>
    const timeline = document.getElementById('live-timeline');
    const POLL_INTERVAL = 10000; // 10 seconds

    function fetchUpdates() {
        console.log('Fetching live updates...');
        fetch('index.php?page=ajax_live')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.news) {
                    updateTimeline(data.news);
                }
            })
            .catch(err => console.error('Update failed:', err));
    }

    function updateTimeline(news) {
        // News API returns sorted by publishedAt (desc)
        // We only show top 7
        const items = news.slice(0, 7);
        
        let hasNew = false;
        
        // Reverse to process oldest to newest for prepending correctly
        // but since we usually prepending the newest one at top, 
        // we can just check if the very first item is different.
        
        items.reverse().forEach(item => {
            const id = btoa(item.url).replace(/=/g, ''); // Simple ID from URL
            if (!document.querySelector(`[data-id="${id}"]`)) {
                // Prepend new item
                renderItem(item, id);
                hasNew = true;
            }
        });

        // Maintain limit of 7
        const currentItems = timeline.querySelectorAll('.timeline-item');
        if (currentItems.length > 7) {
            for (let i = 7; i < currentItems.length; i++) {
                currentItems[i].remove();
            }
        }
    }

    function renderItem(item, id) {
        const div = document.createElement('div');
        div.className = 'timeline-item new-item-highlight';
        div.setAttribute('data-id', id);
        div.style.position = 'relative';
        div.style.marginBottom = '4rem';
        
        // Formatted Time (H:i)
        const date = new Date(item.publishedAt);
        const time = date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
        const host = new URL(item.url).hostname.toUpperCase();

        div.innerHTML = `
            <div style="position: absolute; left: calc(-2rem - 6px); top: 0; width: 10px; height: 10px; background: #dc2626; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 0 2px #dc2626;"></div>
            <div class="time-meta" style="font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; color: #666; margin-bottom: 0.5rem;">
                ${time} &bull; ${host}
            </div>
            <h3 class="serif-headline" style="font-size: 1.6rem; margin: 0 0 1rem 0; line-height: 1.2;">
                <a href="${item.url}" target="_blank" style="text-decoration: none; color: inherit;">
                    ${escapeHtml(item.title)}
                </a>
            </h3>
            ${item.urlToImage ? `<img src="${item.urlToImage}" style="width: 100%; height: 300px; object-fit: cover; border-radius: 4px; margin-bottom: 1rem;">` : ''}
            <p style="font-family: var(--font-sans); line-height: 1.6; color: #444; margin: 0;">
                ${escapeHtml(item.description || '')}
            </p>
            <a href="${item.url}" target="_blank" style="display: inline-block; margin-top: 1rem; font-family: var(--font-sans); font-weight: bold; color: #000; font-size: 0.85rem; text-decoration: underline;">
                View coverage
            </a>
        `;
        
        timeline.prepend(div);
        
        // Remove highlight class after animation
        setTimeout(() => div.classList.remove('new-item-highlight'), 3000);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Start polling
    setInterval(fetchUpdates, POLL_INTERVAL);
</script>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
