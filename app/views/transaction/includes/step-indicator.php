<?php

$currentStep = $currentStep ?? 1;
$cartCount = isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0;
$steps = [
    1 => ['label' => 'Biodata',   'url' => url('/transactions/create')],
    2 => ['label' => 'Kategori',  'url' => url('/transactions/categories')],
    3 => ['label' => 'Detail Produk', 'url' => '#'],
    4 => ['label' => 'Keranjang', 'url' => url('/transactions/cart')],
    5 => ['label' => 'Invoice',   'url' => url('/transactions/invoice')],
];
?>
<nav class="step-indicator" aria-label="Progress pesanan">
    <?php foreach ($steps as $num => $step):
        $isCurrent = $num === $currentStep;
        $isDone    = $num < $currentStep;
        $class     = 'step-indicator__step';
        if ($isCurrent) $class .= ' step-indicator__step--active';
        if ($isDone)    $class .= ' step-indicator__step--done';
    ?>
        <?php if ($num > 1): ?>
            <span class="step-indicator__arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
        <?php endif; ?>
        <span class="<?= $class ?>"<?= $isCurrent ? ' aria-current="step"' : '' ?>>
            <span class="step-indicator__num">
                <?php if ($isDone): ?>
                    <i class="fas fa-check" aria-hidden="true"></i>
                <?php else: ?>
                    <?= $num ?>
                <?php endif; ?>
            </span>
            <span class="step-indicator__label"><?= e($step['label']) ?></span>
            <?php if ($num === 4 && $cartCount > 0): ?>
                <span class="step-indicator__badge"><?= e($cartCount) ?></span>
            <?php endif; ?>
        </span>
    <?php endforeach; ?>
</nav>
