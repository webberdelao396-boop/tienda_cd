</main>
<footer class="site-footer">
  <div class="footer-inner">
    <div>
      <span class="footer-brand"><?= e(cfg('sitio_nombre')) ?></span>
      <p><?= e(cfg('sitio_lema')) ?></p>
    </div>
    <div class="footer-links">
      <a href="/catalogo.php">Catálogo completo</a>
      <a href="/contacto.php">Escríbenos</a>
      <a href="/admin/index.php">Administración</a>
    </div>
    <div class="footer-meta">
      <p><?= e(cfg('sitio_email')) ?></p>
      <p class="footer-copy">© <?= date('Y') ?> · Proyecto académico · Software libre (LAMP)</p>
    </div>
  </div>
</footer>
</body>
</html>
