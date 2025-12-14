<div class="form-group">
    <label for="tgl_transaksi">Tanggal Transaksi <span class="required">*</span></label>
    <input type="date" id="tgl_transaksi" name="tgl_transaksi"
        value="<?php echo isset($data) ? $data['tgl_transaksi'] : ''; ?>" required>
</div>

<div class="form-group">
    <label for="jenis_pelanggan">Jenis Pelanggan <span class="required">*</span></label>
    <select id="jenis_pelanggan" name="jenis_pelanggan" required onchange="togglePelanggan()">
        <option value="">-- Pilih Jenis Pelanggan --</option>
        <option value="Anggota" <?php echo (isset($data) && $data['jenis_pelanggan'] == 'Anggota') ? 'selected' : ''; ?>>
            Anggota</option>
        <option value="Umum" <?php echo (isset($data) && $data['jenis_pelanggan'] == 'Umum') ? 'selected' : ''; ?>>Umum
        </option>
    </select>
</div>

<div class="form-group" id="pelanggan_group" style="display: none;">
    <label for="id_pelanggan">Nama Anggota</label>
    <select id="id_pelanggan" name="id_pelanggan">
        <option value="">-- Pilih Anggota --</option>
        <?php
        $anggota_query = $pdo->query("SELECT id_anggota, nama FROM Anggota ORDER BY nama");
        while ($anggota = $anggota_query->fetch(PDO::FETCH_ASSOC)) {
            $selected = (isset($data) && $data['id_pelanggan'] == $anggota['id_anggota']) ? 'selected' : '';
            echo "<option value='{$anggota['id_anggota']}' {$selected}>{$anggota['nama']}</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label for="metode_pembayaran">Metode Pembayaran <span class="required">*</span></label>
    <select id="metode_pembayaran" name="metode_pembayaran" required>
        <option value="">-- Pilih Metode --</option>
        <option value="Tunai" <?php echo (isset($data) && $data['metode_pembayaran'] == 'Tunai') ? 'selected' : ''; ?>>
            Tunai</option>
        <option value="Transfer" <?php echo (isset($data) && $data['metode_pembayaran'] == 'Transfer') ? 'selected' : ''; ?>>Transfer</option>
        <option value="QRIS" <?php echo (isset($data) && $data['metode_pembayaran'] == 'QRIS') ? 'selected' : ''; ?>>QRIS
        </option>
    </select>
</div>

<div class="form-divider"></div>

<div class="detail-section">
    <h3>Detail Barang</h3>
    <button type="button" class="btn-add-item" onclick="addItemRow()">
        <i class="fas fa-plus"></i> Tambah Item
    </button>

    <div class="table-responsive" style="margin-top: 15px;">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Barang</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 20%;">Subtotal</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody id="items-container">
                <?php if (isset($detail_items) && count($detail_items) > 0): ?>
                    <?php foreach ($detail_items as $index => $item): ?>
                        <tr class="item-row">
                            <td>
                                <select name="items[<?php echo $index; ?>][id_barang]" class="barang-select"
                                    onchange="updateHarga(this)" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php
                                    $barang_query = $pdo->query("SELECT id_barang, nama_barang, harga_jual, stok FROM Barang WHERE stok > 0 ORDER BY nama_barang");
                                    while ($barang = $barang_query->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($item['id_barang'] == $barang['id_barang']) ? 'selected' : '';
                                        echo "<option value='{$barang['id_barang']}' data-harga='{$barang['harga_jual']}' data-stok='{$barang['stok']}' {$selected}>{$barang['nama_barang']} (Stok: {$barang['stok']})</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[<?php echo $index; ?>][harga_satuan]" class="harga-input"
                                    value="<?php echo $item['harga_satuan']; ?>" step="0.01" readonly>
                            </td>
                            <td>
                                <input type="number" name="items[<?php echo $index; ?>][jumlah]" class="jumlah-input"
                                    value="<?php echo $item['jumlah']; ?>" min="1" onchange="updateSubtotal(this)" required>
                            </td>
                            <td>
                                <input type="number" name="items[<?php echo $index; ?>][subtotal]" class="subtotal-input"
                                    value="<?php echo $item['subtotal']; ?>" step="0.01" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn-remove-item" onclick="removeItemRow(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="item-row">
                        <td>
                            <select name="items[0][id_barang]" class="barang-select" onchange="updateHarga(this)" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php
                                $barang_query = $pdo->query("SELECT id_barang, nama_barang, harga_jual, stok FROM Barang WHERE stok > 0 ORDER BY nama_barang");
                                while ($barang = $barang_query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$barang['id_barang']}' data-harga='{$barang['harga_jual']}' data-stok='{$barang['stok']}'>{$barang['nama_barang']} (Stok: {$barang['stok']})</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][harga_satuan]" class="harga-input" step="0.01" readonly>
                        </td>
                        <td>
                            <input type="number" name="items[0][jumlah]" class="jumlah-input" min="1" value="1"
                                onchange="updateSubtotal(this)" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][subtotal]" class="subtotal-input" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn-remove-item" onclick="removeItemRow(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Total Harga:</td>
                    <td>
                        <input type="number" id="total_harga" name="total_harga" step="0.01" readonly required
                            style="font-weight: bold; font-size: 16px;">
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
    .form-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #ddd, transparent);
        margin: 25px 0;
    }

    .detail-section h3 {
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .btn-add-item {
        background: #27ae60;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.3s;
    }

    .btn-add-item:hover {
        background: #229954;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .items-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .items-table th {
        padding: 12px;
        text-align: left;
        font-weight: 500;
    }

    .items-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #eee;
    }

    .items-table tbody tr:hover {
        background: #f8f9fa;
    }

    .items-table select,
    .items-table input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .items-table input[readonly] {
        background: #f5f5f5;
        cursor: not-allowed;
    }

    .btn-remove-item {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-remove-item:hover {
        background: #c0392b;
    }

    #total_harga {
        background: #fff3cd !important;
        border: 2px solid #ffc107 !important;
        cursor: default !important;
    }
</style>

<script>
    let itemIndex = <?php echo isset($detail_items) ? count($detail_items) : 1; ?>;

    function togglePelanggan() {
        const jenis = document.getElementById('jenis_pelanggan').value;
        const pelangganGroup = document.getElementById('pelanggan_group');
        const pelangganSelect = document.getElementById('id_pelanggan');

        if (jenis === 'Anggota') {
            pelangganGroup.style.display = 'block';
            pelangganSelect.required = true;
        } else {
            pelangganGroup.style.display = 'none';
            pelangganSelect.required = false;
            pelangganSelect.value = '';
        }
    }

    function addItemRow() {
        const container = document.getElementById('items-container');
        const row = document.createElement('tr');
        row.className = 'item-row';

        row.innerHTML = `
        <td>
            <select name="items[${itemIndex}][id_barang]" class="barang-select" onchange="updateHarga(this)" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $barang_query = $pdo->query("SELECT id_barang, nama_barang, harga_jual, stok FROM Barang WHERE stok > 0 ORDER BY nama_barang");
                while ($barang = $barang_query->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$barang['id_barang']}' data-harga='{$barang['harga_jual']}' data-stok='{$barang['stok']}'>{$barang['nama_barang']} (Stok: {$barang['stok']})</option>";
                }
                ?>
            </select>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][harga_satuan]" class="harga-input" step="0.01" readonly>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][jumlah]" class="jumlah-input" min="1" value="1" onchange="updateSubtotal(this)" required>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][subtotal]" class="subtotal-input" step="0.01" readonly>
        </td>
        <td>
            <button type="button" class="btn-remove-item" onclick="removeItemRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

        container.appendChild(row);
        itemIndex++;
    }

    function removeItemRow(button) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            button.closest('tr').remove();
            calculateTotal();
        } else {
            alert('Minimal harus ada 1 item barang!');
        }
    }

    function updateHarga(select) {
        const row = select.closest('tr');
        const selectedOption = select.options[select.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga') || 0;

        const hargaInput = row.querySelector('.harga-input');
        hargaInput.value = harga;

        updateSubtotal(row.querySelector('.jumlah-input'));
    }

    function updateSubtotal(input) {
        const row = input.closest('tr');
        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
        const jumlah = parseFloat(input.value) || 0;
        const subtotal = harga * jumlah;

        row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        document.getElementById('total_harga').value = total.toFixed(2);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function () {
        togglePelanggan();
        calculateTotal();
    });
</script>
