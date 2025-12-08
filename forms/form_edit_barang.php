<div class="form-row">
    <div class="form-group">
        <label for="nama_barang">Nama Barang <span class="required">*</span></label>
        <input type="text" name="nama_barang" id="nama_barang" required placeholder="Contoh: Beras Premium" 
               value="<?php echo htmlspecialchars($data['nama_barang']); ?>">
    </div>

    <div class="form-group">
        <label for="harga">Harga <span class="required">*</span></label>
        <input type="number" name="harga" id="harga" required placeholder="Contoh: 75000" step="0.01" min="0" 
               value="<?php echo htmlspecialchars($data['harga_jual']); ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="stok">Stok <span class="required">*</span></label>
        <input type="number" name="stok" id="stok" required placeholder="Contoh: 100" min="0" 
               value="<?php echo htmlspecialchars($data['stok']); ?>">
    </div>

    <div class="form-group">
        <!-- Empty space for alignment -->
    </div>
</div>
