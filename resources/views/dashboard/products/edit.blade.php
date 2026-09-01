@extends('layouts.app')

@section('title', 'تعديل المنتج')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" dir="rtl">
    <div class="flex items-center gap-3">
        <a href="{{ route('dashboard.products.index') }}" class="w-10 h-10 rounded-xl bg-dark-800 border border-dark-700 flex items-center justify-center text-dark-200 hover:text-dark-100 hover:border-dark-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-dark-100">تعديل المنتج: {{ $product->name }}</h1>
    </div>

    <form action="{{ route('dashboard.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="productForm()">
        @csrf
        @method('PUT')

        <div class="bg-dark-900 rounded-2xl border border-dark-800 p-6 space-y-4">
            <h2 class="text-lg font-bold text-dark-100">معلومات المنتج</h2>

            <div>
                <label class="block text-dark-100 text-sm font-medium mb-2">اسم المنتج *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="أدخل اسم المنتج">
                @error('name')
                    <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div x-data="categorySearch()">
                    <label class="block text-dark-100 text-sm font-medium mb-2">الفئة *</label>
                    <div class="relative">
                        <input type="text" x-model="search" @input="filterCategories()" @focus="showDropdown = true" @click.away="showDropdown = false" placeholder="اكتب اسم الفئة..." required class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors">
                        <input type="hidden" name="category_id" :value="selectedId">
                        <div x-show="showDropdown && (filteredCategories.length > 0 || search.length > 0)" x-transition class="absolute z-50 mt-1 w-full bg-dark-800 border border-dark-700 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="cat in filteredCategories" :key="cat.id">
                                <div @click="selectCategory(cat)" class="px-4 py-2.5 text-sm text-dark-200 hover:bg-dark-700 hover:text-dark-100 cursor-pointer transition-colors" x-text="cat.name"></div>
                            </template>
                            <div x-show="search.length > 0 && !filteredCategories.find(c => c.name === search)" @click="createCategory()" class="px-4 py-2.5 text-sm text-blue-400 hover:bg-dark-700 cursor-pointer transition-colors border-t border-dark-700">
                                + إنشاء فئة جديدة: "<span x-text="search"></span>"
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="new_category_name" :value="newCategoryName">
                    @error('category_id')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">SKU (رمز المنتج) *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="مثال: PRD-001">
                    @error('sku')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-dark-100 text-sm font-medium mb-2">الوصف</label>
                <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
                <style>
                    .ql-toolbar.ql-snow { border-color: #374151 !important; border-radius: 12px 12px 0 0 !important; background: #1f2937; }
                    .ql-container.ql-snow { border-color: #374151 !important; border-radius: 0 0 12px 12px !important; background: #111827; min-height: 200px; font-family: 'Cairo', sans-serif; }
                    .ql-editor { color: #f3f4f6 !important; font-size: 14px; line-height: 1.8; direction: rtl; text-align: right; }
                    .ql-editor.ql-blank::before { color: #6b7280 !important; font-style: normal !important; }
                    .ql-snow .ql-stroke { stroke: #9ca3af !important; }
                    .ql-snow .ql-fill { fill: #9ca3af !important; }
                    .ql-snow .ql-picker { color: #d1d5db !important; }
                    .ql-snow .ql-picker-options { background: #1f2937 !important; border-color: #374151 !important; }
                    .ql-snow .ql-active .ql-stroke { stroke: #818cf8 !important; }
                    .ql-snow .ql-active .ql-fill { fill: #818cf8 !important; }
                    .ql-snow .ql-active { color: #818cf8 !important; }
                    .ql-toolbar.ql-snow .ql-formats { margin-left: 12px; }
                </style>
                <div id="editor-container" style="min-height:200px;">{!! old('description', $product->description) !!}</div>
                <input type="hidden" name="description" id="descriptionHidden">
                @error('description')
                    <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="bg-dark-900 rounded-2xl border border-dark-800 p-6 space-y-4">
            <h2 class="text-lg font-bold text-dark-100">الأسعار والمخزون</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">سعر الشراء (DA) *</label>
                    <input type="number" name="buy_price" step="0.01" min="0" value="{{ old('buy_price', $product->buy_price) }}" required x-model="buyPrice" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="0.00">
                    @error('buy_price')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">سعر البيع (DA) *</label>
                    <input type="number" name="sell_price" step="0.01" min="0" value="{{ old('sell_price', $product->sell_price) }}" required x-model="sellPrice" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="0.00">
                    @error('sell_price')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="bg-dark-800 rounded-xl p-3 flex items-center justify-between">
                <span class="text-dark-200 text-sm">هامش الربح</span>
                <span class="text-emerald-400 font-bold" x-text="profitMargin + '%'">0%</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">كمية المخزون *</label>
                    <input type="number" name="stock_quantity" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="0">
                    @error('stock_quantity')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">الحد الأدنى للمخزون</label>
                    <input type="number" name="low_stock_threshold" min="0" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="5">
                    @error('low_stock_threshold')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">الوزن (غ)</label>
                    <input type="number" name="weight" step="0.01" min="0" value="{{ old('weight', $product->weight) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="0">
                    @error('weight')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-dark-100 text-sm font-medium mb-2">صورة المنتج</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 rounded-xl object-cover border border-dark-700">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors file:ml-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer">
                    @error('image')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded bg-dark-800 border-dark-700 text-blue-600 focus:ring-blue-500">
                <label for="is_active" class="text-dark-100 text-sm">منتج نشط (ظاهر في المتجر)</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.products.index') }}" class="px-6 py-2.5 bg-dark-800 hover:bg-dark-700 text-dark-100 rounded-xl text-sm font-medium transition-colors">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">حفظ التعديلات</button>
        </div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var quill = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'اكتب وصف المنتج هنا...',
    direction: 'rtl',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['blockquote'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['link', 'image'],
            ['clean']
        ]
    }
});
var form = quill.container.closest('form');
form.addEventListener('submit', function() {
    document.getElementById('descriptionHidden').value = quill.root.innerHTML;
});
</script>
<script>
function productForm() {
    return {
        buyPrice: '{{ old("buy_price", $product->buy_price) }}',
        sellPrice: '{{ old("sell_price", $product->sell_price) }}',
        get profitMargin() {
            const buy = parseFloat(this.buyPrice) || 0;
            const sell = parseFloat(this.sellPrice) || 0;
            if (buy === 0) return '0.0';
            return (((sell - buy) / buy) * 100).toFixed(1);
        }
    }
}
function categorySearch() {
    const cats = @json($categories ?? []);
    const currentName = '{{ $product->category->name ?? "" }}';
    const currentId = '{{ $product->category_id ?? "" }}';
    return {
        search: currentName,
        showDropdown: false,
        filteredCategories: cats,
        selectedId: currentId,
        newCategoryName: '',
        filterCategories() {
            const q = this.search.toLowerCase();
            this.filteredCategories = cats.filter(c => c.name.toLowerCase().includes(q));
            this.selectedId = '';
            this.newCategoryName = '';
        },
        selectCategory(cat) {
            this.search = cat.name;
            this.selectedId = cat.id;
            this.showDropdown = false;
        },
        createCategory() {
            this.selectedId = '';
            this.newCategoryName = this.search;
            this.showDropdown = false;
        }
    }
}
</script>
@endsection
