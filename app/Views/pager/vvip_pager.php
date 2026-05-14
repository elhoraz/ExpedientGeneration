<?php

/**
 * Pager Template VVIP — Selaras dengan Design System Expedient Generation.
 * 
 * Override template pagination default CI4 agar sesuai estetika glassmorphism.
 * Konfigurasi di app/Config/Pager.php -> $templates['default_full']
 */

$pager->setSurroundCount(2);
?>

<style>
.vvip-pager { display: flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: wrap; }
.vvip-pager a, .vvip-pager span {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 38px; height: 38px; padding: 0 10px;
    border-radius: 10px; font-family: 'Inter', sans-serif; font-size: 0.8rem; font-weight: 600;
    text-decoration: none; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.02); color: var(--text-secondary, #8b9ba8);
}
.vvip-pager a:hover {
    background: rgba(212,175,55,0.15); color: #d4af37;
    border-color: rgba(212,175,55,0.4); transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212,175,55,0.15);
}
.vvip-pager .active {
    background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(212,175,55,0.1));
    color: #d4af37; border-color: rgba(212,175,55,0.5);
    box-shadow: 0 4px 20px rgba(212,175,55,0.2), inset 0 0 0 1px rgba(212,175,55,0.3);
}
.vvip-pager .disabled { opacity: 0.3; pointer-events: none; }
</style>

<nav aria-label="Page navigation">
    <div class="vvip-pager">
        <?php if ($pager->hasPrevious()) : ?>
            <a href="<?= $pager->getFirst() ?>" aria-label="First"><i class="fa-solid fa-angles-left"></i></a>
            <a href="<?= $pager->getPrevious() ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></a>
        <?php else: ?>
            <span class="disabled"><i class="fa-solid fa-angles-left"></i></span>
            <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <?php if ($link['active']) : ?>
                <span class="active"><?= $link['title'] ?></span>
            <?php else : ?>
                <a href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
            <?php endif ?>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <a href="<?= $pager->getNext() ?>" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></a>
            <a href="<?= $pager->getLast() ?>" aria-label="Last"><i class="fa-solid fa-angles-right"></i></a>
        <?php else: ?>
            <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="disabled"><i class="fa-solid fa-angles-right"></i></span>
        <?php endif ?>
    </div>
</nav>
