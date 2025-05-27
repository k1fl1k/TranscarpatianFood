<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Особисті дані</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">
            Оновіть інформацію профілю вашого облікового запису.
        </p>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Аватарка -->
                <div class="col-md-12 mb-4">
                    <label class="form-label">Аватарка</label>
                    <div class="d-flex align-items-start gap-3">
                        <!-- Поточна аватарка -->
                        <div class="text-center">
                            <div id="current-avatar" class="mb-2">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                         alt="{{ Auth::user()->name }}"
                                         class="rounded-circle img-thumbnail"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            @if(Auth::user()->avatar)
                                <form action="{{ route('profile.avatar.remove') }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Ви впевнені, що хочете видалити аватарку?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Видалити
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Завантаження нової аватарки -->
                        <div class="flex-grow-1">
                            <input type="file" class="form-control" id="avatar" name="avatar"
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   onchange="previewAvatar(this)">
                            <div class="form-text">
                                Дозволені формати: JPEG, PNG, JPG, GIF. Максимальний розмір: 2MB.
                            </div>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            <!-- Попередній перегляд -->
                            <div id="avatar-preview" class="mt-2" style="display: none;">
                                <img id="preview-image" class="rounded-circle img-thumbnail"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ім'я -->
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Ім'я</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ Auth::user()->name }}" required autofocus>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ Auth::user()->email }}" required>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Телефон -->
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Номер телефону</label>
                    <input id="phone" name="phone" type="tel" class="form-control" value="{{ Auth::user()->phone }}" required>
                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Дата народження -->
                <div class="col-md-6 mb-3">
                    <label for="birth_date" class="form-label">Дата народження</label>
                    <input id="birth_date" name="birth_date" type="date" class="form-control" value="{{ Auth::user()->birth_date ? Auth::user()->birth_date->format('Y-m-d') : '' }}" required>
                    @error('birth_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Адреса -->
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">Адреса</label>
                    <input id="address" name="address" type="text" class="form-control" value="{{ Auth::user()->address }}">
                    @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Місто -->
                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">Місто</label>
                    <input id="city" name="city" type="text" class="form-control" value="{{ Auth::user()->city }}">
                    @error('city') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Поштовий індекс -->
                <div class="col-md-4 mb-3">
                    <label for="postal_code" class="form-label">Поштовий індекс</label>
                    <input id="postal_code" name="postal_code" type="text" class="form-control" value="{{ Auth::user()->postal_code }}">
                    @error('postal_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Країна -->
                <div class="col-md-4 mb-3">
                    <label for="country" class="form-label">Країна</label>
                    <input id="country" name="country" type="text" class="form-control" value="{{ Auth::user()->country ?? 'Україна' }}">
                    @error('country') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">Зберегти зміни</button>

                @if (session('profile_updated'))
                    <div class="alert alert-success d-inline-block ms-3 mb-0 py-2 px-3">
                        Інформація оновлена!
                    </div>
                @endif

                @if (session('avatar_removed'))
                    <div class="alert alert-info d-inline-block ms-3 mb-0 py-2 px-3">
                        {{ session('avatar_removed') }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(input) {
    const preview = document.getElementById('avatar-preview');
    const previewImage = document.getElementById('preview-image');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
