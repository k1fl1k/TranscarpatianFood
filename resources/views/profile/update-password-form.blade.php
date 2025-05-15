<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Змінити пароль</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">
            Переконайтеся, що ваш обліковий запис використовує надійний пароль для безпеки.
        </p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Поточний пароль -->
                <div class="col-md-12 mb-3">
                    <label for="current_password" class="form-label">Поточний пароль</label>
                    <input id="current_password" name="current_password" type="password" class="form-control" required>
                    @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Новий пароль -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Новий пароль</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Підтвердження пароля -->
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Підтвердження пароля</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                    @error('password_confirmation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">Оновити пароль</button>
                
                @if (session('password_updated'))
                    <div class="alert alert-success d-inline-block ms-3 mb-0 py-2 px-3">
                        Пароль оновлено!
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
