<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Особисті дані</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">
            Оновіть інформацію профілю вашого облікового запису.
        </p>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
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
            </div>
        </form>
    </div>
</div>
