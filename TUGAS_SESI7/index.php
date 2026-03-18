<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Sesi 7</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 bg-white p-5 rounded shadow-sm">

                <h1 class="text-center fw-bold mb-4">Form Input Product</h1>

                <form action="process.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="nama" class="form-control" placeholder="Enter product name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Price</label>
                        <input type="number" name="harga" class="form-control" placeholder="Enter product price">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Enter product description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="kategori" class="form-select">
                            <option value="">Select category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Home">Home</option>
                            <option value="Sports">Sports</option>
                            <option value="Food">Food</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Stock</label>
                        <input type="number" name="stok" class="form-control" placeholder="Enter product stock">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>

            </div>
        </div>
    </div>

</body>

</html>