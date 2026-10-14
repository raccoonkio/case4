@php
    $showPurchaseForm = old('order_type') === 'purchase';
    $showRentalForm = old('order_type') === 'rental';
    $isGuest = session('user_id') === null;
@endphp

<div class="order-modals">
    <section id="purchase-order-modal" class="order-modal" data-order-modal aria-hidden="{{ $showPurchaseForm ? 'false' : 'true' }}" @if (! $showPurchaseForm) hidden @endif>
        <button class="order-modal-backdrop" type="button" data-order-modal-close aria-label="Закрыть окно"></button>
        <div class="order-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="purchase-order-title" tabindex="-1">
            <button class="order-modal-close" type="button" data-order-modal-close aria-label="Закрыть окно">×</button>
            <form id="purchase-order-form" method="POST" action="{{ route('orders.store') }}" class="order-form">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <input type="hidden" name="order_type" value="purchase">
                <h2 id="purchase-order-title">Оформление покупки</h2>
                @if ($isGuest)
                    <p class="order-note">Оформите покупку без регистрации.</p>
                    <div class="guest-order-fields">
                        <label>
                            <span>Имя</span>
                            <input name="guest_name" value="{{ old('guest_name') }}" required>
                        </label>
                        <label>
                            <span>E-mail</span>
                            <input name="guest_email" type="email" value="{{ old('guest_email') }}" required>
                        </label>
                    </div>
                @endif
                <button class="button button-primary" type="submit">Отправить заказ</button>
            </form>
        </div>
    </section>

    <section id="rental-order-modal" class="order-modal" data-order-modal aria-hidden="{{ $showRentalForm ? 'false' : 'true' }}" @if (! $showRentalForm) hidden @endif>
        <button class="order-modal-backdrop" type="button" data-order-modal-close aria-label="Закрыть окно"></button>
        <div class="order-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="rental-order-title" tabindex="-1">
            <button class="order-modal-close" type="button" data-order-modal-close aria-label="Закрыть окно">×</button>
            <form id="rental-order-form" method="POST" action="{{ route('orders.store') }}" class="order-form">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <input type="hidden" name="order_type" value="rental">
                <h2 id="rental-order-title">Оформление аренды</h2>
                @if ($isGuest)
                    <p class="order-note">Оформите аренду без регистрации.</p>
                    <div class="guest-order-fields">
                        <label>
                            <span>Имя</span>
                            <input name="guest_name" value="{{ old('guest_name') }}" required>
                        </label>
                        <label>
                            <span>E-mail</span>
                            <input name="guest_email" type="email" value="{{ old('guest_email') }}" required>
                        </label>
                    </div>
                @endif
                <label class="order-form-field">
                    <span>Срок аренды</span>
                    <select name="rental_term_days" required>
                        <option value="14" @selected(old('rental_term_days', '14') === '14')>2 недели · {{ number_format($book->rentalPrice(14), 0, ',', ' ') }} ₽</option>
                        <option value="30" @selected(old('rental_term_days') === '30')>1 месяц · {{ number_format($book->rentalPrice(30), 0, ',', ' ') }} ₽</option>
                        <option value="90" @selected(old('rental_term_days') === '90')>3 месяца · {{ number_format($book->rentalPrice(90), 0, ',', ' ') }} ₽</option>
                    </select>
                </label>
                <button class="button button-secondary" type="submit">Отправить заказ</button>
            </form>
        </div>
    </section>
</div>

<script>
    let lastModalTrigger = null;

    const closeOrderModal = (modal, restoreFocus = true) => {
        if (! modal) {
            return;
        }

        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');

        if (! document.querySelector('[data-order-modal]:not([hidden])')) {
            document.body.classList.remove('modal-open');
        }

        if (restoreFocus && lastModalTrigger) {
            lastModalTrigger.focus();
        }
    };

    const openOrderModal = (modal, trigger) => {
        document.querySelectorAll('[data-order-modal]').forEach((candidate) => {
            if (candidate !== modal) {
                closeOrderModal(candidate, false);
            }
        });

        lastModalTrigger = trigger;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        modal.querySelector('.order-modal-dialog').focus();
    };

    document.querySelectorAll('[data-order-form-target]').forEach((button) => {
        button.addEventListener('click', () => {
            const form = document.getElementById(button.dataset.orderFormTarget);
            const modal = form?.closest('[data-order-modal]');

            openOrderModal(modal, button);
        });
    });

    document.querySelectorAll('[data-order-modal-close]').forEach((control) => {
        control.addEventListener('click', () => {
            closeOrderModal(control.closest('[data-order-modal]'));
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeOrderModal(document.querySelector('[data-order-modal]:not([hidden])'));
        }
    });

    if (document.querySelector('[data-order-modal]:not([hidden])')) {
        document.body.classList.add('modal-open');
    }

    document.querySelectorAll('[data-rental-term-option]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const rentalSelect = document.querySelector('#rental-order-form [name="rental_term_days"]');

            if (radio.checked && rentalSelect) {
                rentalSelect.value = radio.value;
            }
        });
    });
</script>
