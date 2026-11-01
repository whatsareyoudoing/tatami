{{-- Popup detail pesanan di dashboard: alamat, daftar barang, dan tombol ubah status --}}
@php
    $isOpen = (int) session('open_order') === $order->id;
@endphp

<div class="modal fade" id="order-{{ $order->id }}" tabindex="-1" aria-labelledby="order-{{ $order->id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="order-{{ $order->id }}-title">
                    {{ $order->order_number }}
                    <span class="badge text-bg-{{ $order->status_color }} ms-2 align-middle fs-6">{{ $order->status_label }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                @if ($isOpen && session('error'))
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}</div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Pelanggan</div>
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <div class="small">{{ $order->user->email }}</div>
                        <div class="small text-muted mt-2">Tanggal pesan</div>
                        <div class="small">{{ $order->created_at->translatedFormat('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Dikirim ke</div>
                        <div class="fw-semibold">{{ $order->recipient_name }}</div>
                        <div class="small">{{ $order->phone }}</div>
                        <div class="small">{!! nl2br(e($order->address)) !!}</div>
                        @if ($order->note)
                            <div class="small text-muted mt-2">Catatan</div>
                            <div class="small">{{ $order->note }}</div>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                                @if ($order->status === \App\Models\Order::BARU)
                                    <th class="text-center">Stok saat ini</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->product_name }}
                                        @if ($item->variant_name)
                                            <div class="small text-muted">{{ $item->variant_name }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end text-nowrap">@rupiah($item->price)</td>
                                    <td class="text-end text-nowrap">@rupiah($item->subtotal)</td>
                                    @if ($order->status === \App\Models\Order::BARU)
                                        {{-- Peringatan dini sebelum admin menekan "Dikirim" --}}
                                        <td class="text-center">
                                            @if (!$item->variant)
                                                <span class="badge text-bg-danger" title="Warna ini sudah dihapus dari produk">Dihapus</span>
                                            @elseif ($item->variant->stok < $item->quantity)
                                                <span class="badge text-bg-danger" title="Stok tidak cukup">{{ $item->variant->stok }}</span>
                                            @else
                                                <span class="badge text-bg-light border">{{ $item->variant->stok }}</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total (tanpa ongkir)</th>
                                <th class="text-end text-nowrap">@rupiah($order->total)</th>
                                @if ($order->status === \App\Models\Order::BARU)
                                    <th></th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($order->tracking_number)
                    <div class="bg-light rounded-3 p-3 small">
                        @if ($order->courier)
                            <div><span class="text-muted">Kurir:</span> <strong>{{ $order->courier }}</strong></div>
                        @endif
                        <div><span class="text-muted">No. resi:</span> <strong>{{ $order->tracking_number }}</strong></div>
                        <div><span class="text-muted">Dikirim:</span> {{ $order->shipped_at?->translatedFormat('d M Y H:i') }}</div>
                        @if ($order->completed_at)
                            <div><span class="text-muted">Selesai:</span> {{ $order->completed_at->translatedFormat('d M Y H:i') }}</div>
                        @endif
                    </div>
                @endif
                @if ($order->cancelled_at)
                    <div class="bg-light rounded-3 p-3 small">
                        <div><span class="text-muted">Dibatalkan:</span> {{ $order->cancelled_at->translatedFormat('d M Y H:i') }}</div>
                        @if ($order->cancel_reason)
                            <div><span class="text-muted">Alasan:</span> {{ $order->cancel_reason }}</div>
                        @endif
                    </div>
                @endif

                {{-- Ubah status --}}
                @can('update_order')
                    @if ($order->status === \App\Models\Order::BARU)
                        <hr>
                        <form action="{{ route('order.ship', $order) }}" method="POST"
                              data-confirm="Tandai {{ $order->order_number }} sebagai dikirim? Stok produk akan dikurangi otomatis.">
                            @csrf
                            @method('PATCH')
                            <div class="row g-2 align-items-start">
                                <div class="col-md-4">
                                    <label for="kurir-{{ $order->id }}" class="form-label fw-semibold">Kurir</label>
                                    <input type="text" id="kurir-{{ $order->id }}" name="courier" placeholder="Contoh: JNE REG" maxlength="50" required
                                           value="{{ $isOpen ? old('courier') : '' }}"
                                           @class(['form-control', 'is-invalid' => $isOpen && $errors->has('courier')])>
                                    @if ($isOpen && $errors->has('courier'))
                                        <div class="invalid-feedback">{{ $errors->first('courier') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <label for="resi-{{ $order->id }}" class="form-label fw-semibold">No. Resi</label>
                                    <div class="input-group has-validation">
                                        <input type="text" id="resi-{{ $order->id }}" name="tracking_number" placeholder="Contoh: 1234567890" maxlength="100" required
                                               value="{{ $isOpen ? old('tracking_number') : '' }}"
                                               @class(['form-control', 'is-invalid' => $isOpen && $errors->has('tracking_number')])>
                                        <button type="submit" class="btn btn-tatami"><i class="fas fa-truck me-1"></i> Tandai Dikirim</button>
                                        @if ($isOpen && $errors->has('tracking_number'))
                                            <div class="invalid-feedback">{{ $errors->first('tracking_number') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">Kurir dan no. resi dikirim ke email customer. Stok setiap barang akan dikurangi sesuai jumlah pesanan.</div>
                        </form>
                    @endif
                @endcan
            </div>

            @can('update_order')
                @if (in_array($order->status, [\App\Models\Order::BARU, \App\Models\Order::DIKIRIM]))
                    <div class="modal-footer justify-content-between">
                        @if ($order->status === \App\Models\Order::BARU)
                            <form action="{{ route('order.cancel', $order) }}" method="POST" class="d-flex flex-wrap gap-2 flex-grow-1"
                                  data-confirm="Batalkan pesanan {{ $order->order_number }}? Customer akan dikabari lewat email.">
                                @csrf
                                @method('PATCH')
                                <div class="flex-grow-1">
                                    <input type="text" name="cancel_reason" maxlength="255" aria-label="Alasan pembatalan"
                                           placeholder="Alasan pembatalan (opsional), misal: stok habis"
                                           value="{{ $isOpen ? old('cancel_reason') : '' }}"
                                           @class(['form-control', 'is-invalid' => $isOpen && $errors->has('cancel_reason')])>
                                    @if ($isOpen && $errors->has('cancel_reason'))
                                        <div class="invalid-feedback">{{ $errors->first('cancel_reason') }}</div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-outline-danger align-self-start"><i class="fas fa-times me-1"></i> Batalkan Pesanan</button>
                            </form>
                        @else
                            <span></span>
                            <form action="{{ route('order.complete', $order) }}" method="POST" data-confirm="Tandai {{ $order->order_number }} sebagai selesai?">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-tatami"><i class="fas fa-check me-1"></i> Tandai Selesai</button>
                            </form>
                        @endif
                    </div>
                @endif
            @endcan
        </div>
    </div>
</div>
