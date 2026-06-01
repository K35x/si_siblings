<?php

class TransactionController extends Controller
{
    private const ORDER_FORM_VIEWS = [
        't-shirt' => 'transaction.form.t-shirt',
        'pdh' => 'transaction.form.work-uniform',
        'jersey' => 'transaction.form.jersey',
        'polo-shirt' => 'transaction.form.polo-shirt',
        'seragam-olahraga' => 'transaction.form.sports-uniform',
        'jacket' => 'transaction.form.jacket',
        'hoodie' => 'transaction.form.hoodie',
    ];

    private const MAX_DESIGN_UPLOAD_BYTES = 5242880;

    private const DESIGN_UPLOAD_MIME_TYPES = [
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'webp' => ['image/webp'],
        'pdf' => ['application/pdf'],
    ];

    private ?TransactionModel $transactions = null;
    private ?StockModel $stock = null;

    public function index(): void
    {
        $sidebarRole = $this->resolveSidebarRole();
        $productModel = new ProductModel();

        $this->view('transaction.index', [
            'sidebarRole' => $sidebarRole,
            'activeMenu' => 'status',
            'transactions' => $this->transactions()->all(),
            'categories' => $productModel->allCategories(),
            'escape' => fn ($value) => e($value),
        ]);
    }

    public function create(): void
    {
        $this->view('transaction.create-order', [
            'sidebarRole' => Model::ROLE_KASIR,
            'activeMenu' => 'orders',
            'products' => $this->transactions()->allProducts(),
            'categories' => $this->transactions()->getActiveCategories(),
        ]);
    }

    public function saveCustomerData(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Payload JSON tidak valid']);
            return;
        }
        $customerName = trim($data['customer_name'] ?? '');
        $customerPhone = trim($data['phone_number'] ?? '');
        $projectName = trim($data['project_name'] ?? '');

        if ($customerName === '' || $customerPhone === '' || $projectName === '') {
            echo json_encode(['success' => false, 'message' => 'Nama customer, nomor telepon, dan nama project wajib diisi.']);
            return;
        }

        $_SESSION['customer_name'] = $customerName;
        $_SESSION['customer_phone'] = $customerPhone;
        $_SESSION['project_name'] = $projectName;

        echo json_encode(['success' => true]);
    }

    public function getCustomerData(): void
    {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'customer' => [
                'customer_name' => $_SESSION['customer_name'] ?? '',
                'phone_number' => $_SESSION['customer_phone'] ?? '',
                'project_name' => $_SESSION['project_name'] ?? '',
            ],
        ]);
    }

    public function calculatePrice(): void
    {
        header('Content-Type: application/json');

        $variantId = (int) ($_GET['variant_id'] ?? $_POST['variant_id'] ?? 0);
        $optionId = (int) ($_GET['option_id'] ?? $_POST['option_id'] ?? 0);
        $quantity = $_GET['quantity'] ?? $_POST['quantity'] ?? 0;
        $sleeveType = $_GET['sleeve_type'] ?? $_POST['sleeve_type'] ?? Model::SLEEVE_SHORT;

        if (!$variantId || filter_var($quantity, FILTER_VALIDATE_INT) === false) {
            echo json_encode(['success' => false, 'message' => 'Jumlah harus bilangan bulat']);
            return;
        }

        $variant = $this->transactions()->getVariantPrice($variantId);
        if (!$variant) {
            echo json_encode(['success' => false, 'message' => 'Varian tidak ditemukan.']);
            return;
        }

        $surcharge = $optionId ? $this->transactions()->getPriceSurcharge($optionId) : 0;
        $unitPrice = (float) $variant['price'] + $surcharge;
        if ($sleeveType === Model::SLEEVE_LONG) {
            $unitPrice += (float) ($variant['sleeve_price'] ?? 0);
        }

        echo json_encode([
            'success' => true,
            'unit_price' => $unitPrice,
            'total' => $unitPrice * (int) $quantity,
        ]);
    }

    public function validateQuantity(): void
    {
        header('Content-Type: application/json');

        $variantId = (int) ($_GET['variant_id'] ?? $_POST['variant_id'] ?? 0);
        $quantity = $_GET['quantity'] ?? $_POST['quantity'] ?? 0;

        if (filter_var($quantity, FILTER_VALIDATE_INT) === false) {
            echo json_encode(['success' => false, 'message' => 'Jumlah harus bilangan bulat']);
            return;
        }

        $minimumOrder = $variantId ? $this->transactions()->getVariantMinimumOrder($variantId) : 0;
        $quantity = (int) $quantity;

        echo json_encode([
            'success' => true,
            'valid' => $minimumOrder === 0 || $quantity >= $minimumOrder,
            'minimum_order' => $minimumOrder,
            'message' => $minimumOrder > 0 && $quantity < $minimumOrder ? "Minimal pemesanan {$minimumOrder} pcs. Lanjutkan?" : '',
        ]);
    }

    public function getVariants(): void
    {
        header('Content-Type: application/json');

        $categoryId = (int) ($_GET['category_id'] ?? 0);
        if (!$categoryId) {
            echo json_encode(['success' => false, 'message' => 'Kategori tidak valid.', 'variants' => []]);
            return;
        }

        echo json_encode([
            'success' => true,
            'variants' => $this->transactions()->getVariantsByCategory($categoryId),
            'sablon_types' => $this->transactions()->getSablonTypesByCategory($categoryId),
        ]);
    }

    public function getVariantOptions(): void
    {
        header('Content-Type: application/json');

        $variantId = (int) ($_GET['variant_id'] ?? 0);
        if (!$variantId) {
            echo json_encode(['success' => false, 'message' => 'Varian tidak valid.', 'options' => []]);
            return;
        }

        echo json_encode([
            'success' => true,
            'options' => $this->transactions()->getVariantOptions($variantId),
        ]);
    }

    public function getSablonTypes(): void
    {
        header('Content-Type: application/json');

        $categoryId = (int) ($_GET['category_id'] ?? 0);
        if (!$categoryId) {
            echo json_encode(['success' => false, 'message' => 'Kategori tidak valid.', 'sablon_types' => []]);
            return;
        }

        echo json_encode([
            'success' => true,
            'sablon_types' => $this->transactions()->getSablonTypesByCategory($categoryId),
        ]);
    }

    public function invoice(): void
    {
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            $keranjang = $_SESSION['keranjang'] ?? [];
            $grandTotal = 0;
            foreach ($keranjang as $item) {
                $grandTotal += (float) ($item['price'] ?? 0);
            }

            $this->view('transaction.invoice', [
                'sidebarRole' => Model::ROLE_KASIR,
                'activeMenu' => 'invoice',
                'mode' => 'session',
                'invoice_number' => 'DRAFT',
                'customer_name' => $_SESSION['customer_name'] ?? 'Walk In Customer',
                'customer_phone' => $_SESSION['customer_phone'] ?? '-',
                'project_name' => $_SESSION['project_name'] ?? '',
                'order_date' => $_SESSION['order_date'] ?? date('Y-m-d'),
                'items' => $keranjang,
                'grand_total' => $grandTotal,
            ]);
            return;
        }

        $order = $this->transactions()->findOrder((string) $orderId);
        if (!$order) {
            header('Location: ' . url('/transactions'));
            exit;
        }

        $orderId = (int) $order['order_id'];
        $items = $this->transactions()->findOrderItems($orderId);
        $details = $this->transactions()->findOrderDetails($orderId);
        $designs = $this->transactions()->findOrderDesigns($orderId);
        $paymentSummary = $this->transactions()->getPaymentSummary($orderId);

        $detailsByItem = [];
        foreach ($details as $d) {
            $detailsByItem[(int) $d['order_item_id']][] = $d;
        }
        foreach ($detailsByItem as $itemId => $itemDets) {
            $detailsByItem[$itemId] = TransactionModel::mergeFulfillmentDetails($itemDets);
        }

        $this->view('transaction.invoice', [
            'sidebarRole' => $_SESSION['user']['role'] ?? Model::ROLE_KASIR,
            'activeMenu' => 'invoice',
            'mode' => 'database',
            'invoice_number' => $order['order_code'] ?? ('ORD-' . $orderId),
            'customer_name' => $order['name'] ?? 'Customer',
            'customer_phone' => $order['phone_number'] ?? '-',
            'project_name' => $order['project_name'] ?? '',
            'order_date' => $order['order_date'] ?? null,
            'order' => $order,
            'items' => $items,
            'grand_total' => (float) ($order['grand_total'] ?? 0),
            'detailsByItem' => $detailsByItem,
            'designs' => $designs,
            'payment_summary' => $paymentSummary,
        ]);
    }

    public function categories(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify_unified()) {
                http_response_code(403);
                echo '403 — CSRF token tidak valid';
                return;
            }

            $customerName = trim($_POST['customer_name'] ?? '');
            $customerPhone = trim($_POST['phone_number'] ?? '');
            $projectName = trim($_POST['project_name'] ?? '');

            $_SESSION['customer_name'] = $customerName;
            $_SESSION['customer_phone'] = $customerPhone;
            $_SESSION['project_name'] = $projectName;
            $_SESSION['order_date'] = $_POST['order_date'] ?? null;

            $errors = [];
            if ($customerName === '') {
                $errors[] = 'Nama customer wajib diisi.';
            }
            if ($customerPhone === '') {
                $errors[] = 'Nomor telepon wajib diisi.';
            }
            if ($projectName === '') {
                $errors[] = 'Nama project wajib diisi.';
            }

            if ($errors !== []) {
                $_SESSION['error'] = implode(' ', $errors);
                header('Location: ' . url('/transactions/create'));
                exit;
            }

            header('Location: ' . url('/transactions/categories'));
            exit;
        }

        $this->view('transaction.select-category', [
            'sidebarRole' => Model::ROLE_KASIR,
            'activeMenu' => 'orders',
            'categories' => $this->transactions()->getCategoriesWithPrices(),
        ]);
    }

    public function detail(): void
    {
        $orderCode = $_GET['id'] ?? null;

        if (!$orderCode) {
            header('Location: ' . url('/transactions'));
            exit;
        }

        $order = $this->transactions()->findOrder($orderCode);
        if ($order && !empty($order['cancelled_by_user_id'])) {
            $cancelledBy = $this->transactions()->findUserName((int) $order['cancelled_by_user_id']);
            $order['cancelled_by_name'] = $cancelledBy ?: '-';
        }

        if (!$order) {
            http_response_code(404);
            $this->view('transaction.detail-pesanan', [
                'sidebarRole' => $this->resolveSidebarRole(),
                'activeMenu' => 'status',
                'id_pesanan' => $orderCode,
                'order' => null,
                'items' => [],
                'user_role' => Model::ROLE_KASIR,
            ]);
            return;
        }

        $items = $this->transactions()->findOrderItems((int) ($order['order_id'] ?? 0));
        $details = $this->transactions()->findOrderDetails((int) ($order['order_id'] ?? 0));
        $payments = $this->transactions()->findOrderPayments((int) ($order['order_id'] ?? 0));
        $paymentSummary = $this->transactions()->getPaymentSummary((int) ($order['order_id'] ?? 0));
        $designs = $this->transactions()->findOrderDesigns((int) ($order['order_id'] ?? 0));
        $history = $this->transactions()->findOrderHistory((int) ($order['order_id'] ?? 0));
        $sidebarRole = $this->resolveSidebarRole();

        $detailsByItem = [];
        foreach ($details as $d) {
            $detailsByItem[(int) $d['order_item_id']][] = $d;
        }
        foreach ($detailsByItem as $itemId => $itemDets) {
            $detailsByItem[$itemId] = TransactionModel::mergeFulfillmentDetails($itemDets);
        }

        $this->view('transaction.detail-pesanan', [
            'sidebarRole' => $sidebarRole,
            'activeMenu' => 'status',
            'id_pesanan' => $order['order_code'],
            'kategori' => $items[0]['category_name'] ?? '-',
            'nama_pelanggan' => $order['name'] ?? '-',
            'telepon' => $order['phone_number'] ?? '-',
            'tanggal' => format_date_id($order['order_date'] ?? null, true),
            'order' => $order,
            'items' => $items,
            'detailsByItem' => $detailsByItem,
            'payments' => $payments,
            'payment_summary' => $paymentSummary,
            'designs' => $designs,
            'history' => $history,
            'user_role' => $sidebarRole,
            'current_user_id' => (int) ($_SESSION['user']['user_id'] ?? 0),
        ]);
    }

    public function cart(): void
    {
        if (isset($_GET['edit'])) {
            $id = (int) $_GET['edit'];
            if (isset($_SESSION['keranjang'][$id])) {
                $item = $_SESSION['keranjang'][$id];
                $_SESSION['edit_item'] = $item;
                $_SESSION['edit_index'] = $id;

                $categoryMap = (new ProductModel())->categoryFormRouteMap();
                $route = $categoryMap[$item['category']] ?? '/transactions/categories';
                header('Location: ' . url($route));
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify_unified()) {
                http_response_code(403);
                echo '403 — CSRF token tidak valid';
                return;
            }

            if (isset($_POST['hapus'])) {
                $id = (int) $_POST['hapus'];
                if (isset($_SESSION['keranjang'][$id])) {
                    unset($_SESSION['keranjang'][$id]);
                    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
                }
                header('Location: ' . url('/transactions/cart'));
                exit;
            }

            if (isset($_POST['clear'])) {
                unset($_SESSION['keranjang'], $_SESSION['invoice_number']);
                header('Location: ' . url('/transactions/cart'));
                exit;
            }

            $this->saveCartItem();
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $customerName = trim($_SESSION['customer_name'] ?? '');
        $customerPhone = trim($_SESSION['customer_phone'] ?? '');
        $projectName = trim($_SESSION['project_name'] ?? '');
        if (!empty($_SESSION['keranjang']) && ($customerName === '' || $customerPhone === '' || $projectName === '')) {
            $_SESSION['error'] = 'Lengkapi nama customer, nomor telepon, dan nama project sebelum melanjutkan.';
            header('Location: ' . url('/transactions/create'));
            exit;
        }

        $this->view('transaction.cart', [
            'sidebarRole' => Model::ROLE_KASIR,
            'activeMenu' => 'cart',
        ]);
    }

    public function tshirt(): void
    {
        $this->productForm('t-shirt');
    }
    public function pdh(): void
    {
        $this->productForm('pdh');
    }
    public function jersey(): void
    {
        $this->productForm('jersey');
    }
    public function poloshirt(): void
    {
        $this->productForm('polo-shirt');
    }
    public function seragamolahraga(): void
    {
        $this->productForm('seragam-olahraga');
    }
    public function jacket(): void
    {
        $this->productForm('jacket');
    }
    public function hoodie(): void
    {
        $this->productForm('hoodie');
    }

    private function productForm(string $formSource): void
    {
        $view = self::ORDER_FORM_VIEWS[$formSource] ?? null;
        if ($view === null) {
            header('Location: ' . url('/transactions/categories'));
            exit;
        }

        $productModel = new ProductModel();
        $category = $productModel->findByFormKey($formSource);
        $categoryId = $category ? (int) $category['category_id'] : 0;
        $variants = $categoryId ? $productModel->variantsByCategory($categoryId) : [];
        $minimumOrder = (int) ($variants[0]['minimum_order'] ?? 0);

        $this->view($view, [
            'sidebarRole' => Model::ROLE_KASIR,
            'activeMenu' => 'orders',
            'formSource' => $formSource,
            'colors' => $productModel->allColors(),
            'sizes' => $productModel->allSizes(),
            'variants' => $variants,
            'sablonTypes' => $categoryId ? $productModel->sablonTypesByCategory($categoryId) : [],
            'sizeSurcharges' => $categoryId ? $productModel->sizeSurchargesByCategory($categoryId) : [],
            'minimumOrder' => $minimumOrder,
            'editIndex' => $_SESSION['edit_index'] ?? null,
            'editItem' => $_SESSION['edit_item'] ?? null,
        ]);
    }

    private function parseCartQuantities(array $sizes): array
    {
        $qtyShort = [];
        $qtyLong = [];
        foreach ($sizes as $sz) {
            $qtyShort[$sz] = (int) ($_POST['quantity_short_' . $sz] ?? 0);
            $qtyLong[$sz] = (int) ($_POST['quantity_long_' . $sz] ?? 0);
        }
        $totals = [];
        foreach ($sizes as $sz) {
            $totals[$sz] = $qtyShort[$sz] + $qtyLong[$sz];
        }
        return [$totals, $qtyShort, $qtyLong];
    }

    private function parseCartColors(array $sizes): array
    {
        $warnaPerSize = ['short' => [], 'long' => []];
        $customColors = [];
        foreach ($sizes as $sz) {
            $ws = trim($_POST['warna_short_' . $sz] ?? '');
            $wl = trim($_POST['warna_long_' . $sz] ?? '');
            if ($ws === '__custom') {
                $ws = trim($_POST['custom_warna_short_' . $sz] ?? '');
                if ($ws) {
                    $customColors[$sz . '_short'] = $ws;
                }
            }
            if ($wl === '__custom') {
                $wl = trim($_POST['custom_warna_long_' . $sz] ?? '');
                if ($wl) {
                    $customColors[$sz . '_long'] = $wl;
                }
            }
            $warnaPerSize['short'][$sz] = $ws;
            $warnaPerSize['long'][$sz] = $wl;
        }
        return [$warnaPerSize, $customColors];
    }

    private function calculateCartPrice(int $totalQty, int $hargaPerPcs, array $sizes, array $qtyLong, float $longRate, ?int $variantId = null): array
    {
        $sizeSurcharges = $variantId !== null ? $this->transactions()->sizeSurchargesByName($variantId) : [];
        $sizeSurchargeTotal = 0;
        $surchargeBreakdown = [];
        foreach ($sizes as $size => $qty) {
            $qty = (int) $qty;
            $rate = (float) ($sizeSurcharges[$size] ?? 0);
            if ($qty > 0 && $rate > 0) {
                $lineTotal = $qty * $rate;
                $sizeSurchargeTotal += $lineTotal;
                $surchargeBreakdown[$size] = [
                    'qty' => $qty,
                    'rate' => $rate,
                    'total' => $lineTotal,
                ];
            }
        }

        $longQty = array_sum($qtyLong);
        $longSurcharge = $longQty * $longRate;
        $totalHarga = ($totalQty * $hargaPerPcs) + $sizeSurchargeTotal + $longSurcharge;

        return [$totalHarga, [
            'xxl_qty' => $surchargeBreakdown['XXL']['qty'] ?? 0,
            'xxl_total' => $surchargeBreakdown['XXL']['total'] ?? 0,
            'size' => $surchargeBreakdown,
            'long_qty' => $longQty,
            'long_rate' => $longRate,
            'long_total' => $longSurcharge,
        ]];
    }

    private function designUploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran file maksimal 5 MB',
            UPLOAD_ERR_PARTIAL => 'Upload file tidak lengkap. Silakan coba lagi.',
            default => 'Gagal upload file.',
        };
    }

    private function makeDesignFilename(string $suffix, string $extension): string
    {
        return time() . '_' . bin2hex(random_bytes(8)) . '_' . $suffix . '.' . $extension;
    }

    private function isAllowedDesignMime(string $tmpPath, string $extension): bool
    {
        if (!isset(self::DESIGN_UPLOAD_MIME_TYPES[$extension])) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return false;
        }

        $mime = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        return is_string($mime) && in_array($mime, self::DESIGN_UPLOAD_MIME_TYPES[$extension], true);
    }

    private function uploadDesignFiles(array $existingDesigns = []): array
    {
        $desainFiles = [];
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/desain/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        for ($i = 1; $i <= Model::MAX_DESIGN_UPLOADS; $i++) {
            $existing = $existingDesigns[$i - 1] ?? null;
            $note = trim($_POST["note_desain_{$i}"] ?? '');
            if (!empty($_FILES["desain_{$i}"]['name']) && $_FILES["desain_{$i}"]['error'] !== UPLOAD_ERR_OK) {
                throw new \InvalidArgumentException($this->designUploadErrorMessage((int) $_FILES["desain_{$i}"]['error']));
            }
            if (!empty($_FILES["desain_{$i}"]['name']) && $_FILES["desain_{$i}"]['error'] === UPLOAD_ERR_OK) {
                if ((int) ($_FILES["desain_{$i}"]['size'] ?? 0) > self::MAX_DESIGN_UPLOAD_BYTES) {
                    throw new \InvalidArgumentException('Ukuran file maksimal 5 MB');
                }
                $ext = strtolower(pathinfo($_FILES["desain_{$i}"]['name'], PATHINFO_EXTENSION));
                if (!array_key_exists($ext, self::DESIGN_UPLOAD_MIME_TYPES)) {
                    throw new \InvalidArgumentException('Tipe file tidak valid');
                }
                if (!$this->isAllowedDesignMime($_FILES["desain_{$i}"]['tmp_name'], $ext)) {
                    throw new \InvalidArgumentException('Tipe file tidak valid');
                }
                $filename = $this->makeDesignFilename((string) $i, $ext);
                $dest = $uploadDir . $filename;
                if (!move_uploaded_file($_FILES["desain_{$i}"]['tmp_name'], $dest)) {
                    throw new \InvalidArgumentException('Gagal upload file.');
                }

                $desainFiles[] = [
                    'filename' => $filename,
                    'url' => 'uploads/desain/' . $filename,
                    'note' => $note,
                ];
            } elseif ($existing) {
                $existing['note'] = $note !== '' ? $note : ($existing['note'] ?? '');
                $desainFiles[] = $existing;
            } elseif ($note !== '') {
                $desainFiles[] = ['filename' => '', 'url' => '', 'note' => $note];
            }
        }
        return $desainFiles;
    }

    private function saveCartItem(): void
    {
        $kategori = $_POST['kategori'] ?? '';
        $availableSizes = $this->activeSizeNames();

        $variantId = (int) ($_POST['variant_id'] ?? 0);
        $variantId = $variantId > 0 ? $variantId : null;

        [$sizes, $qtyShort, $qtyLong] = $this->parseCartQuantities($availableSizes);
        [$warnaPerSize, $customColors] = $this->parseCartColors($availableSizes);

        $integerErrors = $this->validateIntegerInputs($availableSizes);
        if (!empty($integerErrors)) {
            $_SESSION['validation_errors'] = $integerErrors;
            $_SESSION['old_input'] = $_POST;
            $formSource = trim($_POST['form_source'] ?? '');
            $formSource = isset(self::ORDER_FORM_VIEWS[$formSource]) ? $formSource : '';
            header('Location: ' . url($formSource !== '' ? '/transactions/form/' . $formSource : '/transactions/cart'));
            exit;
        }

        $colorErrors = [];
        foreach ($availableSizes as $size) {
            if (($qtyShort[$size] ?? 0) > 0 && ($warnaPerSize['short'][$size] ?? '') === '') {
                $colorErrors[] = "Warna lengan pendek ukuran {$size} wajib dipilih.";
            }
            if (($qtyLong[$size] ?? 0) > 0 && ($warnaPerSize['long'][$size] ?? '') === '') {
                $colorErrors[] = "Warna lengan panjang ukuran {$size} wajib dipilih.";
            }
        }
        if (!empty($colorErrors)) {
            $_SESSION['validation_errors'] = $colorErrors;
            $_SESSION['old_input'] = $_POST;
            $formSource = trim($_POST['form_source'] ?? '');
            $formSource = isset(self::ORDER_FORM_VIEWS[$formSource]) ? $formSource : '';
            header('Location: ' . url($formSource !== '' ? '/transactions/form/' . $formSource : '/transactions/cart'));
            exit;
        }

        $warnaShortSummary = $this->summarizeColors($warnaPerSize['short']);
        $warnaLongSummary = $this->summarizeColors($warnaPerSize['long']);
        $totalQty = array_sum($sizes);

        if ($kategori === '' || $totalQty < 1) {
            $_SESSION['validation_errors'] = [];
            if ($kategori === '') {
                $_SESSION['validation_errors'][] = 'Kategori wajib diisi.';
            }
            if ($totalQty < 1) {
                $_SESSION['validation_errors'][] = 'Minimal 1 ukuran harus diisi.';
            }
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        if ($variantId === null || !$this->transactions()->getVariantPrice($variantId)) {
            $_SESSION['validation_errors'] = ['Varian produk sudah nonaktif atau tidak tersedia. Pilih varian aktif.'];
            $_SESSION['old_input'] = $_POST;
            $formSource = trim($_POST['form_source'] ?? '');
            $formSource = isset(self::ORDER_FORM_VIEWS[$formSource]) ? $formSource : '';
            header('Location: ' . url($formSource !== '' ? '/transactions/form/' . $formSource : '/transactions/cart'));
            exit;
        }

        $inactiveOptionErrors = $this->transactions()->inactiveVariantOptionSelections($variantId, $sizes, $warnaPerSize, $qtyShort, $qtyLong);
        if (!empty($inactiveOptionErrors)) {
            $_SESSION['validation_errors'] = $inactiveOptionErrors;
            $_SESSION['old_input'] = $_POST;
            $formSource = trim($_POST['form_source'] ?? '');
            $formSource = isset(self::ORDER_FORM_VIEWS[$formSource]) ? $formSource : '';
            header('Location: ' . url($formSource !== '' ? '/transactions/form/' . $formSource : '/transactions/cart'));
            exit;
        }

        $hargaPerPcs = (int) ($_POST['paket_bahan'] ?? 0);
        $sablonPrice = (int) ($_POST['sablon_price'] ?? 0);
        $hargaPerPcs += $sablonPrice;

        $sablonTypeId = !empty($_POST['sablon_type_id']) ? (int) $_POST['sablon_type_id'] : null;
        $sablonTypeName = null;
        if ($sablonTypeId) {
            $sablonType = (new ProductModel())->findSablonType($sablonTypeId);
            $sablonTypeName = $sablonType ? $sablonType['sablon_name'] : null;
        }

        $longSurchargeRate = (float) ($_POST['sleeve_price'] ?? 5000);
        if ($variantId !== null) {
            $variantPrice = $this->transactions()->getVariantPrice($variantId);
            if ($variantPrice) {
                $longSurchargeRate = (float) ($variantPrice['sleeve_price'] ?? 0);
            }
        }
        [$totalHarga, $surcharge] = $this->calculateCartPrice($totalQty, $hargaPerPcs, $sizes, $qtyLong, $longSurchargeRate, $variantId);
        $existingDesigns = [];
        if (isset($_POST['index_edit']) && $_POST['index_edit'] !== '') {
            $editIdx = (int) $_POST['index_edit'];
            $existingDesigns = $_SESSION['keranjang'][$editIdx]['desain'] ?? [];
        }
        try {
            $desainFiles = $this->uploadDesignFiles($existingDesigns);
        } catch (\InvalidArgumentException $e) {
            $_SESSION['validation_errors'] = [$e->getMessage()];
            $_SESSION['old_input'] = $_POST;
            $formSource = trim($_POST['form_source'] ?? '');
            $formSource = isset(self::ORDER_FORM_VIEWS[$formSource]) ? $formSource : '';
            header('Location: ' . url($formSource !== '' ? '/transactions/form/' . $formSource : '/transactions/cart'));
            exit;
        }

        $itemBaru = [
            'category' => $kategori ?: '-',
            'variant_id' => $variantId,
            'material' => trim($_POST['jenis_bahan'] ?? '-'),
            'warna' => $warnaShortSummary ?: '-',
            'warna_per_size' => $warnaPerSize,
            'warna_summary' => [
                'short' => $warnaShortSummary,
                'long' => $warnaLongSummary,
            ],
            'custom_colors' => $customColors,
            'sablon_type_id' => $sablonTypeId,
            'sablon' => $sablonTypeName ?? '-',
            'sablon_price' => $sablonPrice,
            'rincian' => $sizes,
            'quantity_short' => $qtyShort,
            'quantity_long' => $qtyLong,
            'quantity' => $totalQty,
            'price' => $totalHarga,
            'unit_price' => $hargaPerPcs,
            'surcharge' => $surcharge,
            'desain' => $desainFiles,
            'catatan' => trim($_POST['order_notes'] ?? ''),
        ];

        if (isset($_POST['index_edit']) && $_POST['index_edit'] !== '') {
            $idx = (int) $_POST['index_edit'];
            if (!isset($_SESSION['keranjang'][$idx])) {
                $_SESSION['validation_errors'] = ['Item yang ingin diubah tidak ditemukan.'];
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . url('/transactions/cart'));
                exit;
            }
            $_SESSION['keranjang'][$idx] = $itemBaru;
            unset($_SESSION['edit_item'], $_SESSION['edit_index']);
        } else {
            $_SESSION['keranjang'][] = $itemBaru;
        }

        unset($_SESSION['invoice_number']);
    }

    private function validateIntegerInputs(array $sizes): array
    {
        $errors = [];
        foreach ($sizes as $size) {
            foreach (["quantity_short_{$size}", "quantity_long_{$size}"] as $field) {
                $value = $_POST[$field] ?? '';
                if ($value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $errors[] = 'Jumlah harus bilangan bulat';
                    return $errors;
                }
            }
        }

        foreach (['paket_bahan', 'sablon_price'] as $field) {
            $value = $_POST[$field] ?? '';
            if ($value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                $errors[] = 'Harga harus bilangan bulat';
                return $errors;
            }
        }

        return $errors;
    }

    private function summarizeColors(array $sizeColors): string
    {
        $grouped = [];
        foreach ($sizeColors as $size => $color) {
            if ($color !== '') {
                $grouped[$color][] = $size;
            }
        }

        if (empty($grouped)) {
            return '';
        }

        $parts = [];
        foreach ($grouped as $color => $sizes) {
            $parts[] = $color . ' (' . implode(',', $sizes) . ')';
        }

        return implode(', ', $parts);
    }

    private function activeSizeNames(): array
    {
        return array_column((new ProductModel())->allSizes(), 'size_name');
    }

    private function transactions(): TransactionModel
    {
        if ($this->transactions === null) {
            $this->transactions = new TransactionModel();
        }

        return $this->transactions;
    }

    private function stock(): StockModel
    {
        if ($this->stock === null) {
            $this->stock = new StockModel();
        }

        return $this->stock;
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('/transactions/invoice'));
            exit;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo '403 — CSRF token tidak valid';
            return;
        }

        $keranjang = $_SESSION['keranjang'] ?? [];
        if (empty($keranjang)) {
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $customerName = trim($_SESSION['customer_name'] ?? '');
        $customerPhone = trim($_SESSION['customer_phone'] ?? '');
        $projectName = trim($_SESSION['project_name'] ?? '');
        if ($customerName === '' || $customerPhone === '' || $projectName === '') {
            $_SESSION['error'] = 'Lengkapi nama customer, nomor telepon, dan nama project sebelum menyimpan pesanan.';
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $grandTotal = 0.0;
        foreach ($keranjang as $item) {
            $grandTotal += (float) ($item['price'] ?? 0);
            if ((int) ($item['quantity'] ?? 0) < 1) {
                $_SESSION['error'] = 'Jumlah item harus lebih dari 0.';
                header('Location: ' . url('/transactions/cart'));
                exit;
            }

            $variantId = (int) ($item['variant_id'] ?? 0);
            if ($variantId < 1 || !$this->transactions()->getVariantPrice($variantId)) {
                $_SESSION['error'] = 'Keranjang berisi varian yang sudah nonaktif atau tidak tersedia. Hapus item tersebut lalu pilih varian aktif.';
                header('Location: ' . url('/transactions/cart'));
                exit;
            }

            $inactiveOptionErrors = $this->transactions()->inactiveVariantOptionSelections(
                $variantId,
                $item['rincian'] ?? [],
                $item['warna_per_size'] ?? ['short' => [], 'long' => []],
                $item['quantity_short'] ?? [],
                $item['quantity_long'] ?? [],
            );
            if (!empty($inactiveOptionErrors)) {
                $_SESSION['error'] = 'Keranjang berisi opsi varian yang sudah nonaktif: ' . implode(' ', $inactiveOptionErrors);
                header('Location: ' . url('/transactions/cart'));
                exit;
            }
        }

        $initialPayment = null;
        $paymentAmount = (float) ($_POST['initial_payment_amount'] ?? 0);
        if ($paymentAmount > 0) {
            $paymentMethod = $_POST['initial_payment_method'] ?? '';
            $paymentDate = trim($_POST['initial_payment_date'] ?? '');
            $validDate = DateTime::createFromFormat('Y-m-d', $paymentDate);
            $today = new DateTime('today');
            $minDate = new DateTime('2020-01-01');

            if (!isset(Model::PAYMENT_METHODS[$paymentMethod])
                || $paymentAmount > $grandTotal
                || $paymentDate === ''
                || !$validDate
                || $validDate->format('Y-m-d') !== $paymentDate
                || $validDate < $minDate
                || $validDate > $today
            ) {
                $_SESSION['error'] = 'Data pembayaran awal tidak valid. Periksa metode, jumlah, dan tanggal pembayaran.';
                header('Location: ' . url('/transactions/invoice'));
                exit;
            }

            $initialPayment = [
                'payment_method' => $paymentMethod,
                'amount' => $paymentAmount,
                'payment_date' => $paymentDate . ' ' . date('H:i:s'),
            ];
        }

        try {
            $order = $this->transactions()->createOrder([
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'project_name' => $projectName,
                'order_date' => $_SESSION['order_date'] ?? date('Y-m-d'),
                'user_id' => $_SESSION['user']['user_id'] ?? null,
                'items' => $keranjang,
            ]);
        } catch (\Throwable $e) {
            error_log('[store()] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $_SESSION['error'] = 'Gagal menyimpan pesanan. Silakan coba lagi.';
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $initialPaymentRecorded = false;
        if ($initialPayment !== null) {
            $orderUserId = (int) ($_SESSION['user']['user_id'] ?? 0);
            if ($orderUserId < 1) {
                $_SESSION['error'] = 'Pesanan berhasil disimpan, tetapi pembayaran awal gagal: user tidak terotentikasi. Silakan catat pembayaran dari detail pesanan.';
                header('Location: ' . url('/transactions/detail?id=' . (int) $order['order_id']));
                exit;
            }
            try {
                $this->transactions()->recordPayment((int) $order['order_id'], $initialPayment, $orderUserId);
                $initialPaymentRecorded = true;
            } catch (\Throwable $e) {
                error_log('[store.initialPayment] ' . $e->getMessage());
                $_SESSION['error'] = 'Pesanan berhasil disimpan, tetapi pembayaran awal gagal dicatat. Silakan catat pembayaran dari detail pesanan.';
            }
        }

        unset($_SESSION['keranjang'], $_SESSION['invoice_number'], $_SESSION['customer_name'], $_SESSION['customer_phone'], $_SESSION['project_name'], $_SESSION['order_date'], $_SESSION['edit_item'], $_SESSION['edit_index']);

        $_SESSION['toast_success'] = 'Pesanan #' . e($order['order_code'] ?? '') . ' berhasil disimpan.' . ($initialPaymentRecorded ? ' Pembayaran awal berhasil dicatat.' : '');
        if ($initialPayment !== null && !$initialPaymentRecorded) {
            $_SESSION['toast_success'] .= ' PERINGATAN: Pembayaran awal gagal dicatat.';
        }
        header('Location: ' . url('/transactions'));
        exit;
    }

    public function updateDesign(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $designId = (int) ($_POST['design_id'] ?? 0);
        $noteValue = $_POST['notes'] ?? $_POST['note'] ?? null;
        $note = $noteValue !== null ? trim((string) $noteValue) : null;
        if ($designId < 1) {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
            return;
        }

        if (!$this->transactions()->canAccessDesign($designId, (int) $_SESSION['user']['user_id'], $_SESSION['user']['role'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengubah desain ini']);
            return;
        }

        $filename = null;
        if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => $this->designUploadErrorMessage((int) $_FILES['file']['error'])]);
            return;
        }
        if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/desain/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if ((int) ($_FILES['file']['size'] ?? 0) > self::MAX_DESIGN_UPLOAD_BYTES) {
                echo json_encode(['success' => false, 'message' => 'Ukuran file maksimal 5 MB']);
                return;
            }
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if (!array_key_exists($ext, self::DESIGN_UPLOAD_MIME_TYPES) || !$this->isAllowedDesignMime($_FILES['file']['tmp_name'], $ext)) {
                echo json_encode(['success' => false, 'message' => 'Tipe file tidak valid']);
                return;
            }
            $filename = $this->makeDesignFilename('edit', $ext);
            $dest = $uploadDir . $filename;
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                echo json_encode(['success' => false, 'message' => 'Gagal upload file.']);
                return;
            }
        }

        try {
            $ok = $this->transactions()->updateDesign($designId, $filename, $note);
        } catch (Throwable $e) {
            if ($filename !== null) {
                @unlink(dirname(__DIR__, 2) . '/public/uploads/desain/' . $filename);
            }
            error_log('[TransactionController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
            return;
        }
        if (!$ok && $filename !== null) {
            @unlink(dirname(__DIR__, 2) . '/public/uploads/desain/' . $filename);
        }
        echo json_encode($ok ? ['success' => true] : ['success' => false, 'message' => 'File tidak ditemukan.']);
    }

    public function deleteDesign(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $designId = (int) ($_POST['design_id'] ?? 0);
        if ($designId < 1) {
            echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
            return;
        }

        if (!$this->transactions()->canAccessDesign($designId, (int) $_SESSION['user']['user_id'], $_SESSION['user']['role'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus desain ini']);
            return;
        }

        $ok = $this->transactions()->deleteDesign($designId);
        echo json_encode($ok ? ['success' => true] : ['success' => false, 'message' => 'File tidak ditemukan.']);
    }

    public function recordPayment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('/transactions'));
            exit;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $orderId = (int) ($_POST['order_id'] ?? 0);
        $paymentMethod = $_POST['payment_method'] ?? '';
        $amount = (float) ($_POST['amount'] ?? 0);
        $paymentDate = trim($_POST['payment_date'] ?? '');
        $userId = (int) $_SESSION['user']['user_id'];

        $validDate = DateTime::createFromFormat('Y-m-d', $paymentDate);
        if ($orderId < 1 || !isset(Model::PAYMENT_METHODS[$paymentMethod]) || $amount <= 0 || $paymentDate === '' || !$validDate || $validDate->format('Y-m-d') !== $paymentDate) {
            $_SESSION['error'] = 'Data pembayaran tidak valid.';
            header('Location: ' . url($orderId > 0 ? '/transactions/detail?id=' . $orderId : '/transactions'));
            exit;
        }

        $userRole = $_SESSION['user']['role'] ?? '';
        $order = $this->transactions()->getOrderById($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Order tidak ditemukan.';
            header('Location: ' . url('/transactions'));
            exit;
        }
        if ($userRole !== Model::ROLE_OWNER && ($userId <= 0 || (int) ($order['user_id'] ?? 0) !== $userId)) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengubah pesanan ini.';
            header('Location: ' . url('/transactions/detail?id=' . $orderId));
            exit;
        }

        try {
            $this->transactions()->recordPayment($orderId, [
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'payment_date' => $paymentDate . ' ' . date('H:i:s'),
            ], $userId);
        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . url('/transactions/detail?id=' . $orderId));
            exit;
        } catch (\Throwable $e) {
            error_log('[recordPayment] ' . $e->getMessage());
            $_SESSION['error'] = 'Gagal mencatat pembayaran.';
            header('Location: ' . url('/transactions/detail?id=' . $orderId));
            exit;
        }

        $_SESSION['success'] = 'Pembayaran berhasil dicatat.';
        header('Location: ' . url('/transactions/detail?id=' . $orderId));
        exit;
    }

    public function updateStatus(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $orderId = (int) ($_POST['order_id'] ?? 0);
        $newStatus = $_POST['order_status'] ?? '';
        $allowed = Model::ALLOWED_ORDER_STATUSES;
        $userId = $_SESSION['user']['user_id'] ?? null;
        $userRole = $_SESSION['user']['role'] ?? '';

        if ($orderId < 1 || !in_array($newStatus, $allowed, true)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
            return;
        }

        try {
            $result = $this->transactions()->updateOrderStatus(
                $orderId,
                $newStatus,
                (int) $userId,
                $userRole,
                trim($_POST['reason'] ?? ''),
                $_POST['notes'] ?? null,
            );

            if (isset($result['http_code'])) {
                http_response_code((int) $result['http_code']);
                unset($result['http_code']);
            }
            echo json_encode($result);
        } catch (\Throwable $e) {
            error_log('[updateStatus] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Gagal mengubah status. Silakan coba lagi.']);
        }
    }

    public function voidPayment(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $paymentId = (int) ($_POST['payment_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');
        $userId = (int) $_SESSION['user']['user_id'];
        $userRole = $_SESSION['user']['role'] ?? '';

        if ($paymentId < 1) {
            echo json_encode(['success' => false, 'message' => 'Payment ID tidak valid']);
            return;
        }

        if (mb_strlen($reason) < 5) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Alasan void wajib diisi minimal 5 karakter.']);
            return;
        }

        $order = $this->transactions()->getOrderByPaymentId($paymentId);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Order tidak ditemukan untuk pembayaran ini.']);
            return;
        }

        if (($order['order_status'] ?? '') === Model::ORDER_STATUS_CANCELLED) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Pembayaran tidak bisa di-void karena pesanan sudah dibatalkan.']);
            return;
        }

        if ($userRole !== Model::ROLE_OWNER) {
            if ($userId <= 0 || (int) ($order['user_id'] ?? 0) !== $userId) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus pembayaran ini']);
                return;
            }
        }

        $result = $this->transactions()->voidPayment($paymentId, $userId, $userRole, $reason);
        echo json_encode($result);
    }

    public function refundPayment(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $paymentId = (int) ($_POST['payment_id'] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');
        $userId = (int) $_SESSION['user']['user_id'];
        $userRole = $_SESSION['user']['role'] ?? '';

        if ($userRole !== Model::ROLE_OWNER) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Hanya Owner yang dapat memproses refund.']);
            return;
        }

        if ($paymentId < 1 || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
            return;
        }

        if (mb_strlen($reason) < 10) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Alasan refund wajib diisi minimal 10 karakter.']);
            return;
        }

        $order = $this->transactions()->getOrderByPaymentId($paymentId);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Order tidak ditemukan untuk pembayaran ini.']);
            return;
        }

        if (($order['order_status'] ?? '') === Model::ORDER_STATUS_CANCELLED) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Refund tidak bisa diproses karena pesanan sudah dibatalkan.']);
            return;
        }

        $result = $this->transactions()->refundPayment($paymentId, $userId, $amount, $reason);
        echo json_encode($result);
    }

    private function resolveSidebarRole(): string
    {
        $sessionRole = (
            session_status() === PHP_SESSION_ACTIVE
            && isset($_SESSION['user']['role'])
        )
            ? $_SESSION['user']['role']
            : null;

        $role = $sessionRole ?? Model::ROLE_KASIR;

        return in_array($role, [Model::ROLE_KASIR, Model::ROLE_OWNER], true) ? $role : Model::ROLE_KASIR;
    }
}
