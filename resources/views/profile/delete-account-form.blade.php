<div class="card shadow-sm border-danger">
    <div class="card-header bg-white text-danger">
        <h5 class="card-title mb-0">Видалення облікового запису</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">
            Після видалення вашого облікового запису всі його ресурси та дані будуть безповоротно видалені. Перед видаленням облікового запису, будь ласка, завантажте будь-які дані чи інформацію, які ви хочете зберегти.
        </p>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Видалити обліковий запис
        </button>
        
        <!-- Модальне вікно підтвердження видалення -->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Підтвердження видалення</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Ви впевнені, що хочете видалити свій обліковий запис?</p>
                        <p class="text-muted small">Після видалення облікового запису всі його ресурси та дані будуть безповоротно видалені. Будь ласка, введіть свій пароль для підтвердження.</p>
                        
                        <form action="{{ route('profile.destroy') }}" method="POST" id="deleteAccountForm">
                            @csrf
                            @method('DELETE')
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input id="password" name="password" type="password" class="form-control" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Скасувати</button>
                        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteAccountForm').submit()">Видалити обліковий запис</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
