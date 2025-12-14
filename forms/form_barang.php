<div class="form-row">
    <div class="form-group">
        <label for="nama_barang">Nama Barang <span class="required">*</span></label>
        <input type="text" name="nama_barang" id="nama_barang" required placeholder="Contoh: Pulpen"
            value="<?php echo isset($data) ? htmlspecialchars($data['nama_barang']) : ''; ?>">
    </div>
    <div class="form-group">
        <label for="kategori">Kategori <span class="required">*</span></label>
        <input type="text" name="kategori" id="kategori" required placeholder="Contoh: Alat Tulis"
            value="<?php echo isset($data) ? htmlspecialchars($data['kategori']) : ''; ?>">
    </div>

</div>
<div class="form-row">
    <div class="form-group">
        <label for="harga_beli">Harga Beli <span class="required">*</span></label>
        <input type="number" name="harga_beli" id="harga_beli" required placeholder="Contoh: 4000" step="100" min="0"
            value="<?php echo isset($data) ? htmlspecialchars($data['harga_beli']) : ''; ?>">
    </div>
    <div class="form-group">
        <label for="harga_jual">Harga Jual <span class="required">*</span></label>
        <input type="number" name="harga_jual" id="harga_jual" required placeholder="Contoh: 5000" step="100" min="0"
            value="<?php echo isset($data) ? htmlspecialchars($data['harga_jual']) : ''; ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="stok">Stok <span class="required">*</span></label>
        <input type="number" name="stok" id="stok" required placeholder="Contoh: 100" min="0"
            value="<?php echo isset($data) ? htmlspecialchars($data['stok']) : ''; ?>">
    </div>
</div>
