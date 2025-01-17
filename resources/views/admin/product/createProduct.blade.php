@extends("admin.app")
@section("content")
<div class="container">
    <h1 class="mb-4 text-center text-primary">Thêm Sản Phẩm</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mt-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Tên sản phẩm -->
            <div class="form-group mb-4">
                <label for="name">Tên Sản Phẩm</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
            </div>

            <!-- Mô tả sản phẩm -->
            <div class="form-group mb-4">
                <label for="description">Mô Tả</label>
                <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    <div class="alert alert-danger">
                        {{ $errors->first('description') }}
                    </div>
                @endif
            </div>

            <!-- Giá Cũ sản phẩm -->
            <div class="form-group mb-4">
                <label for="price_old">Giá Cũ</label>
                <input class="form-control" name="price_old" id="price_old" value="{{ old('price_old') }}">
                @if ($errors->has('price_old'))
                    <div class="alert alert-danger">
                        {{ $errors->first('price_old') }}
                    </div>
                @endif
            </div>

            <!-- Giá Mới sản phẩm -->
            <div class="form-group mb-4">
                <label for="price_new">Giá Mới</label>
                <input class="form-control" name="price_new" id="price_new" value="{{ old('price_new') }}">
                @if ($errors->has('price_new'))
                    <div class="alert alert-danger">
                        {{ $errors->first('price_new') }}
                    </div>
                @endif
            </div>
            <div class="form-group mb-4">
                <label for="img_prd">Ảnh</label>
                <input type="file" name="img_prd" class="form-control" >
            </div>

            <!-- Danh mục sản phẩm -->
            <div class="form-group mb-4">
                <label>Danh Mục Sản Phẩm</label>
                <select name="category" class="form-control">
                    @foreach($category as $value)
                        <option value="{{ $value->id }}" {{ old('category') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Biến thể sản phẩm -->
            <h3>Biến Thể Sản Phẩm</h3>

            <div id="variations" class="border" style="padding: 10px">
                <div class="variation">
                    <div class="form-group">
                        <label for="variation_price">Giá Biến Thể</label>
                        <input type="number" name="variations[0][price]" class="form-control" value="{{ old('variations.0.price') }}">
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity">Số Lượng</label>
                        <input type="number" name="variations[0][stock_quantity]" class="form-control" value="{{ old('variations.0.stock_quantity') }}">
                    </div>

                    <div class="form-group">
                        <label for="image">Ảnh</label>
                        <input type="file" name="variations[0][image]" class="form-control">
                    </div>
                    <div id="variations">
                        <div class="variation">
                            <div class="form-group mb-4">
                                <label>Color</label>
                                <select name="variations[0][attributes][color]" class="form-control attribute-select color-select">
                                    <option value="">Chọn màu</option>
                                    @foreach ($colorValues as $color)
                                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label>Size</label>
                                <select name="variations[0][attributes][size]" class="form-control attribute-select size-select">
                                    <option value="">Chọn size</option>
                                    @foreach ($sizeValues as $size)
                                        <option value="{{ $size->id }}">{{ $size->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    


                    <hr class="my-4 border-top border-3 border-primary shadow-sm">
                </div>


            </div>

            <!-- Nút thêm và xóa biến thể -->
            <button type="button" id="addVariation" class="btn btn-primary mt-3">Thêm Biến Thể</button>
            <button type="button" id="removeVariation" class="btn btn-danger mt-3">Xóa Biến Thể Gần Nhất</button>

            <!-- Nút lưu sản phẩm -->
            <button type="submit" class="btn btn-success mt-3">Lưu Sản Phẩm</button>
        </form>
    </div>
</div>

{{-- <script>
    let selectedCombinations = [];

    // Khóa các tùy chọn đã được chọn trong các dropdown
    function updateDisabledOptions() {
        selectedCombinations = [];

        document.querySelectorAll('.variation').forEach(variation => {
            const size = variation.querySelector('.size-select').value;
            const color = variation.querySelector('.color-select').value;

            if (size && color) {
                selectedCombinations.push(`${size}-${color}`);
            }
        });

        document.querySelectorAll('.variation').forEach(variation => {
            const sizeSelect = variation.querySelector('.size-select');
            const colorSelect = variation.querySelector('.color-select');

            if (sizeSelect && colorSelect) {
                colorSelect.querySelectorAll('option').forEach(option => {
                    const combination = `${sizeSelect.value}-${option.value}`;
                    option.disabled = selectedCombinations.includes(combination);
                });
            }
        });
    }

    // Thêm biến thể mới
    document.getElementById('addVariation').addEventListener('click', () => {
        const newVariation = document.querySelector('.variation').outerHTML.replace(/\[0\]/g, `[${Date.now()}]`);
        document.getElementById('variations').insertAdjacentHTML('beforeend', newVariation);
        updateDisabledOptions();
    });

    // Xóa biến thể gần nhất
    document.getElementById('removeVariation').addEventListener('click', () => {
        const variations = document.querySelectorAll('.variation');
        if (variations.length > 1) {
            variations[variations.length - 1].remove();
            updateDisabledOptions();
        }
    });

    // Lắng nghe sự thay đổi trong các dropdown
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('attribute-select')) {
            updateDisabledOptions();
        }
    });

    // Khởi tạo
    updateDisabledOptions();
</script> --}}
{{-- <script>
    let selectedCombinations = [];

    // Khóa các tùy chọn đã được chọn trong các dropdown
    function updateDisabledOptions() {
        selectedCombinations = [];

        // Lấy tất cả các variation đã chọn
        document.querySelectorAll('.variation').forEach(variation => {
            const size = variation.querySelector('.size-select').value;
            const color = variation.querySelector('.color-select').value;

            if (size && color) {
                selectedCombinations.push(`${size}-${color}`);
            }
        });

        // Cập nhật các tùy chọn bị khóa
        document.querySelectorAll('.variation').forEach(variation => {
            const sizeSelect = variation.querySelector('.size-select');
            const colorSelect = variation.querySelector('.color-select');

            if (sizeSelect && colorSelect) {
                colorSelect.querySelectorAll('option').forEach(option => {
                    const combination = `${sizeSelect.value}-${option.value}`;
                    option.disabled = selectedCombinations.includes(combination);
                });

                sizeSelect.querySelectorAll('option').forEach(option => {
                    const combination = `${option.value}-${colorSelect.value}`;
                    option.disabled = selectedCombinations.includes(combination);
                });
            }
        });
    }

    // Thêm biến thể mới
    document.getElementById('addVariation').addEventListener('click', () => {
        const newVariation = document.querySelector('.variation').outerHTML.replace(/\[0\]/g, `[${Date.now()}]`);
        document.getElementById('variations').insertAdjacentHTML('beforeend', newVariation);
        updateDisabledOptions();
    });

    // Xóa biến thể gần nhất
    document.getElementById('removeVariation').addEventListener('click', () => {
        const variations = document.querySelectorAll('.variation');
        if (variations.length > 1) {
            variations[variations.length - 1].remove();
            updateDisabledOptions();
        }
    });

    // Lắng nghe sự thay đổi trong các dropdown
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('attribute-select')) {
            updateDisabledOptions();
        }
    });

    // Khởi tạo
    updateDisabledOptions();
</script> --}}
<script>
    let selectedCombinations = [];

// Hàm cập nhật danh sách các tùy chọn bị khóa
function updateDisabledOptions() {
    selectedCombinations = [];

    // Duyệt qua tất cả các biến thể đã tồn tại
    document.querySelectorAll('.variation').forEach(variation => {
        const size = variation.querySelector('.size-select')?.value;
        const color = variation.querySelector('.color-select')?.value;

        if (size && color) {
            selectedCombinations.push(`${size}-${color}`);
        }
    });

    // Khóa các tùy chọn trùng lặp
    document.querySelectorAll('.variation').forEach(variation => {
        const sizeSelect = variation.querySelector('.size-select');
        const colorSelect = variation.querySelector('.color-select');

        if (sizeSelect && colorSelect) {
            const currentSize = sizeSelect.value;
            const currentColor = colorSelect.value;

            // Khóa các tùy chọn trong dropdown Color
            colorSelect.querySelectorAll('option').forEach(option => {
                const combination = `${currentSize}-${option.value}`;
                option.disabled = selectedCombinations.includes(combination) && option.value !== currentColor;
            });

            // Khóa các tùy chọn trong dropdown Size
            sizeSelect.querySelectorAll('option').forEach(option => {
                const combination = `${option.value}-${currentColor}`;
                option.disabled = selectedCombinations.includes(combination) && option.value !== currentSize;
            });
        }
    });
}

// Thêm biến thể mới
document.getElementById('addVariation').addEventListener('click', () => {
    const variationsContainer = document.getElementById('variations');
    const newIndex = Date.now(); // Sử dụng timestamp để tạo chỉ số duy nhất
    const firstVariation = document.querySelector('.variation');
    
    // Clone HTML của biến thể đầu tiên
    const newVariationHTML = firstVariation.outerHTML.replace(/\[0\]/g, `[${newIndex}]`);
    
    // Thêm biến thể mới vào container
    variationsContainer.insertAdjacentHTML('beforeend', newVariationHTML);

    // Làm mới các dropdown và cập nhật trạng thái khóa
    updateDisabledOptions();
});

// Xóa biến thể gần nhất
document.getElementById('removeVariation').addEventListener('click', () => {
    const variations = document.querySelectorAll('.variation');
    if (variations.length > 1) {
        variations[variations.length - 1].remove(); // Xóa biến thể cuối cùng
        updateDisabledOptions();
    }
});

// Lắng nghe thay đổi trên các dropdown
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('attribute-select')) {
        updateDisabledOptions();
    }
});

// Khởi tạo khi tải trang
updateDisabledOptions();

</script>
@endsection
