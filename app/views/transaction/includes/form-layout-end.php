        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
window.TRANSACTION_DYNAMIC_OPTIONS_ENDPOINT = '<?= url('/transactions/variant-options') ?>';
</script>
<script src="<?= asset('js/transaction-dynamic-options.js') ?>"></script>
</body>
</html>
