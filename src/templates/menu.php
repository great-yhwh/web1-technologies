<div class="button-panel">
    <form method="GET" action="">
        <?php foreach ($menus as $item): ?>
            <button type="submit" name="page" value="<?= htmlspecialchars($item['page']) ?>" class="task-btn">
                <?= htmlspecialchars($item['title']) ?>
            </button>
        <?php endforeach; ?>
    </form>
</div>