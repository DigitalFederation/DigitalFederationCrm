

<x-information-box title="Information" body="Products are used in Memberships and on the Order Builder. Please make sure that all the fields are accurate and complete before submitting." />


<div class="card">

    <section>


        <div class="sm:flex sm:items-top space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
            <div class="sm:w-1/2">
                <label class="block text-sm font-medium mb-1" for="name">{{ __('Name') }} <span
                        class="text-rose-500">*</span></label>
                <input id="name"
                        class="form-input w-full {{ $errors->has('name') ? 'border-rose-300' : '' }}"
                        type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required/>
                @if($errors->has('name'))
                    <div class="text-xs mt-1 text-rose-500 h-2">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="sm:w-1/4">
                <label class="block text-sm font-medium mb-1" for="code">{{ __('Product code') }}</label>
                <input id="code" class="form-input w-full {{ $errors->has('code') ? 'border-rose-300' : '' }}"
                        type="text"
                        min="0"
                        maxlength="50"
                        name="code"
                        value="{{ old('code', $product->code ?? '') }}"/>

                <div class="text-xs mt-1 text-rose-500 h-2">
                    @if($errors->has('code'))
                        {{ $errors->first('code') }}
                    @endif
                </div>
            </div>

            <div class="sm:w-1/4">
                <label class="block text-sm font-medium mb-1" for="price">{{ __('Price') }}
                    (€)</label>
                <input id="price"
                        pattern="^\\$?(([1-9](\\d*|\\d{0,2}(,\\d{3})*))|0)(\\.\\d{1,2})?$"
                        class="form-input w-full {{ $errors->has('price') ? 'border-rose-300' : '' }}"
                        type="text"
                        min="0"
                        name="price"
                        value="{{ old('price', $product->price ?? '') }}"/>
                <div class="text-xs mt-1"> {{ __('* Price in euros. Ex: 12,90') }} </div>

                <div class="text-xs mt-1 text-rose-500 h-2">
                    @if($errors->has('price'))
                        {{ $errors->first('price') }}
                    @endif
                </div>
            </div>

            <div class="sm:w-1/4">
                <label class="block text-sm font-medium mb-1" for="tax_percentage">{{ __('Tax %') }}</label>
                <input id="tax_percentage"
                        pattern="^\\$?(([1-9](\\d*|\\d{0,2}(,\\d{3})*))|0)(\\.\\d{1,2})?$"
                        class="form-input w-full {{ $errors->has('tax_percentage') ? 'border-rose-300' : '' }}"
                        type="text"
                        min="0"
                        name="tax_percentage"
                        value="{{ old('tax_percentage', $product->tax_percentage ?? '') }}"/>
                <div class="text-xs mt-1"> {{ __('*Leave 0 if no payment is required') }} </div>

                <div class="text-xs mt-1 text-rose-500 h-2">
                    @if($errors->has('price'))
                        {{ $errors->first('price') }}
                    @endif
                </div>
            </div>
        </div>

        <div class="sm:flex sm:items-top space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
            <div class="sm:w-full">
                <label class="block text-sm font-medium mb-1"
                        for="description">{{ __('Description') }} <span
                        class="text-rose-500">*</span></label>
                <textarea id="description" rows="7"
                            class="form-input w-full {{ $errors->has('description') ? 'border-rose-300' : '' }}"
                            name="description">{{ old('description', $product->description ?? '') }}</textarea>
                @if($errors->has('description'))
                    <div class="text-xs mt-1 text-rose-500 h-2">
                        {{ $errors->first('description') }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Panel footer -->
    <x-forms.card-form-submit backRoute="admin.products.index" :buttonText="__('Save record')"/>

</div>
