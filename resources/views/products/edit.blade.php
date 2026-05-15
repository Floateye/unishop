<x-admin-layout>
    <x-status/>
    <div class="admin-container">
        <section id="edit-product" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-edit"></i> Edit Product: {{ $product->name }}</h2>
                <p>Update product details and save changes.</p>
            </div>
            <div class="divider"></div>
            <div class="admin-form-card">
                <form id="edit-product-form" method="POST" action="{{ route('products.update', $product) }}" class="auth-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="auth-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="auth-field">
                            <label for="price">Price (SAR)</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="category_id">Category</label>
                        <select class="category-selection" id="category_id" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="auth-field">
                        <label>Current Image</label>
                        @if($product->image)
                            <div style="margin-bottom: 1rem;">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image" style="max-height: 150px; border-radius: 8px; border: 1px solid #eaeaea;">
                            </div>
                        @else
                            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">No image uploaded yet.</p>
                        @endif
                        <label>Update Image <span style="font-size:0.8rem;font-weight:400;color:#888;">(leave blank to keep current)</span></label>
                        <div class="image-label">
                            <input type="file" id="image" name="image">
                            <button type="button" onclick="document.getElementById('image').value = '';" class="clear-button">
                                <i class="fa fa-times"></i> Clear
                            </button>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" id="quantity" required min="0">
                    </div>

                    <div class="auth-field">
                        <label>Sizes <span style="font-size:0.8rem;font-weight:400;color:#888;">(leave all unchecked if not applicable)</span></label>
                        <div class="size-checkboxes">
                            @php
                                $productSizes = old('size', is_array($product->size) ? $product->size : json_decode($product->size, true) ?? []);
                            @endphp
                            @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                                <label class="size-checkbox-label">
                                    <input type="checkbox" name="size[]" value="{{ $sz }}"
                                        {{ in_array($sz, $productSizes) ? 'checked' : '' }}>
                                    {{ $sz }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="admin-form-actions" style="display: flex; gap: 1rem;">
                        <button type="submit" class="auth-submit">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ route('dashboard') }}#manage" class="auth-submit" style="background-color: #6c757d; text-align: center; text-decoration: none;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-admin-layout>
