<?php
/** partials.php  -  Componentes reutilizables de la web publica */

/**
 * Pinta una tarjeta de producto para las cuadriculas.
 * $p = fila de la tabla productos (con cat_slug del JOIN).
 */
function tarjeta_producto(array $p): void
{
    $slug    = $p['cat_slug'] ?? 'libros';
    $inicial = strtoupper(mb_substr($p['titulo'], 0, 1));
    $agotado = (int)$p['stock'] <= 0;
    ?>
    <a class="prod-card" href="/producto.php?id=<?= (int)$p['id'] ?>">
      <div class="prod-cover <?= e($slug) ?>">
        <?php if (!empty($p['destacado'])): ?>
          <span class="badge-destacado">Destacado</span>
        <?php endif; ?>
        <span class="cover-letter"><?= e($inicial) ?></span>
        <span class="cover-type"><?= e($p['cat_nombre'] ?? '') ?></span>
      </div>
      <div class="prod-body">
        <span class="prod-title"><?= e($p['titulo']) ?></span>
        <span class="prod-artist"><?= e($p['autor_artista']) ?></span>
        <span class="prod-meta"><?= e($p['condicion']) ?><?= $p['anio'] ? ' · ' . (int)$p['anio'] : '' ?></span>
        <div class="prod-foot">
          <span class="prod-price"><?= precio((float)$p['precio']) ?></span>
          <?php if ($agotado): ?>
            <span class="stock-pill agotado">Agotado</span>
          <?php else: ?>
            <span class="stock-pill"><?= (int)$p['stock'] ?> disp.</span>
          <?php endif; ?>
        </div>
      </div>
    </a>
    <?php
}
