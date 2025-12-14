<?php include(__DIR__ . '/layout/Header.php'); ?>
<?php
require_once __DIR__ . '/../Models/Article.php';

$articleModel = new Article();
$latestArticles = [];

try {
    $latestArticles = $articleModel->getLatest(5);
} catch (Exception $e) {
    // fail quietly on homepage if DB is not ready
}
?>
<section class="home-intro">
    <h2>Welcome to the Digital Newsstand</h2>
    <p>Browse the latest news and magazines.</p>
</section>

<?php if (!empty($latestArticles)): ?>
<section class="home-latest">
    <h3>Latest Articles</h3>

    <div class="home-latest-carousel">
        <button class="home-carousel-arrow prev" type="button" aria-label="Previous article">&#10094;</button>

        <div class="home-latest-viewport">
            <div class="home-latest-track">
                <?php foreach ($latestArticles as $index => $a): ?>
                    <article
                        class="home-article-card"
                        data-slide-index="<?= (int)$index ?>"
                    >
                        <?php if (!empty($a['thumbnail'])): ?>
                            <img
                                src="<?= htmlspecialchars($a['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="Thumbnail for <?= htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') ?>"
                            >
                        <?php endif; ?>
                        <div class="home-article-card-content">
                            <h4><?= htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') ?></h4>
                            <a href="?page=article&id=<?= (int)$a['id'] ?>">Read</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="home-carousel-arrow next" type="button" aria-label="Next article">&#10095;</button>
    </div>

    <div class="home-latest-dots" aria-hidden="true">
        <?php foreach ($latestArticles as $index => $a): ?>
            <button
                class="home-latest-dot<?= $index === 0 ? ' is-active' : '' ?>"
                type="button"
                data-target-slide="<?= (int)$index ?>"
            ></button>
        <?php endforeach; ?>
    </div>
</section>
</section>
<?php endif; ?>

<section class="home-games-cta" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 3rem 1rem; color: white; text-align: center; margin-top: 2rem;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Take a Break with Our Mini-Games</h2>
        <p style="font-size: 1.25rem; color: #cbd5e1; margin-bottom: 2rem;">Challenge yourself with our daily puzzles. Play Wordle and more!</p>
        <a href="?page=games" style="display: inline-block; padding: 1rem 2rem; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 1.1rem; transition: background 0.2s;">Visit Games Arcade →</a>
    </div>
</section>

<script>
// Lightweight carousel logic for the Latest Articles hero, similar to ecommerce ad sliders.
(function () {
    const track = document.querySelector('.home-latest-track');
    if (!track) return;

    let slides = Array.from(track.querySelectorAll('.home-article-card'));
    if (slides.length <= 1) return; // no need for carousel

    const prevBtn = document.querySelector('.home-carousel-arrow.prev');
    const nextBtn = document.querySelector('.home-carousel-arrow.next');
    const dots    = Array.from(document.querySelectorAll('.home-latest-dot'));

    const slideCount = slides.length;

    // Create clones for seamless infinite looping (like ecommerce hero sliders)
    const firstClone = slides[0].cloneNode(true);
    const lastClone  = slides[slides.length - 1].cloneNode(true);
    track.appendChild(firstClone);
    track.insertBefore(lastClone, slides[0]);

    // Re‑query slides so list includes clones
    slides = Array.from(track.querySelectorAll('.home-article-card'));

    let currentIndex = 1; // start at first real slide (after the prepended lastClone)
    let timerId = null;
    const defaultTransition = window.getComputedStyle(track).transition;

    function update() {
        // Calculate offset accounting for card margins (24px on each side = 48px total)
        // Each card is calc(100% - 48px) wide with 24px margin on each side
        // So we move by 100% of viewport width per slide
        const offset = -currentIndex * 100;
        track.style.transform = 'translateX(' + offset + '%)';

        const logicalIndex = (currentIndex - 1 + slideCount) % slideCount; // 0-based among real slides
        dots.forEach((dot, i) => {
            if (i === logicalIndex) {
                dot.classList.add('is-active');
            } else {
                dot.classList.remove('is-active');
            }
        });
    }

    function go(delta) {
        currentIndex += delta;
        track.style.transition = defaultTransition;
        update();
    }

    function goTo(logicalIndex) {
        // map logical slide (0..slideCount-1) to physical index in track
        currentIndex = logicalIndex + 1; // +1 because index 0 is lastClone
        track.style.transition = defaultTransition;
        update();
    }

    function startAuto() {
        timerId = window.setInterval(function () {
            go(1); // always move forward, looping using clones
        }, 7000);
    }

    function resetAuto() {
        if (timerId) {
            window.clearInterval(timerId);
        }
        startAuto();
    }

    // When we hit a clone at either end, jump back to the real slide without visible reverse animation
    track.addEventListener('transitionend', function () {
        if (currentIndex === 0) {
            // moved left onto lastClone; snap to last real slide
            track.style.transition = 'none';
            currentIndex = slideCount;
            update();
            void track.offsetWidth; // reflow
            track.style.transition = defaultTransition;
        } else if (currentIndex === slideCount + 1) {
            // moved right onto firstClone; snap to first real slide
            track.style.transition = 'none';
            currentIndex = 1;
            update();
            void track.offsetWidth;
            track.style.transition = defaultTransition;
        }
    });

    function resetAuto() {
        if (timerId) {
            window.clearInterval(timerId);
        }
        startAuto();
    }

    prevBtn && prevBtn.addEventListener('click', function () {
        go(-1);
        resetAuto();
    });

    nextBtn && nextBtn.addEventListener('click', function () {
        go(1);
        resetAuto();
    });

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            goTo(index);
            resetAuto();
        });
    });

    // Initialize starting position at first real slide
    track.style.transition = 'none';
    update();
    void track.offsetWidth;
    track.style.transition = defaultTransition;
    startAuto();
})();
</script>

<?php include(__DIR__ . '/layout/Footer.php'); ?>
