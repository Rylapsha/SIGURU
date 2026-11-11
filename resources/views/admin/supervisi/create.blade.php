@extends('layouts.admin')

@section('title', 'Tambah Jadwal Supervisi')
@section('page_title', 'Tambah Jadwal Supervisi')

@section('content')
<div class="container-fluid px-0">

    @if ($errors->any())
    <div class="alert alert-danger mb-5">
        <div class="fw-semibold mb-2">Periksa kembali input Anda:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
                <div>
                    <h2 class="fw-bold text-gray-900 mb-1">Tambah Jadwal Supervisi</h2>
                    <div class="text-muted">Isi informasi jadwal supervisi yang akan dilaksanakan.</div>
                </div>

                <a href="{{ route('admin.supervisi.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>

            <form action="{{ route('admin.supervisi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-5">
                    <div class="col-md-6">
                        <label for="guru_id" class="form-label fw-semibold">Nama Guru</label>

                        <div class="custom-select-picker" data-guru-picker data-custom-select>
                            <button type="button"
                                class="custom-select-trigger form-control text-start"
                                aria-expanded="false"
                                aria-label="Pilih guru">
                                <span class="custom-select-value">
                                    {{ old('guru_id') ? ($guru->firstWhere('id', old('guru_id'))?->nama_lengkap ?? 'Pilih guru') : 'Pilih guru' }}
                                </span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </button>

                            <div class="custom-select-menu d-none" role="listbox">
                                <button type="button" class="custom-select-option {{ old('guru_id') === '' ? 'selected' : '' }}" data-value="" data-label="Pilih guru" disabled aria-disabled="true">
                                    <span>Pilih guru</span>
                                </button>

                                @foreach($guru as $item)
                                @php
                                $mapels = array_values(array_filter(array_map('trim', preg_split('/[;,]/', (string) ($item->mata_pelajaran ?? '')))));
                                @endphp
                                <button type="button"
                                    class="custom-select-option {{ old('guru_id') == $item->id ? 'selected' : '' }}"
                                    data-value="{{ $item->id }}"
                                    data-label="{{ $item->nama_lengkap }}"
                                    data-mapels='@json($mapels)'>
                                    <span>{{ $item->nama_lengkap }}</span>
                                </button>
                                @endforeach
                            </div>

                            <input type="hidden" name="guru_id" value="{{ old('guru_id') }}" id="guru_id" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="mata_pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>

                        <div class="custom-select-picker" data-subject-picker>
                            <button type="button"
                                class="custom-select-trigger form-control text-start"
                                aria-expanded="false"
                                aria-label="Pilih mata pelajaran"
                                disabled>
                                <span class="custom-select-value">
                                    {{ old('mata_pelajaran') ?: 'Pilih guru terlebih dahulu' }}
                                </span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </button>

                            <div class="custom-select-menu d-none" role="listbox">
                                <button type="button" class="custom-select-option" data-value="" data-label="Pilih guru terlebih dahulu" disabled aria-disabled="true">
                                    <span>Pilih guru terlebih dahulu</span>
                                </button>
                            </div>

                            <input type="hidden" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" id="mata_pelajaran" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nama_supervisor" class="form-label fw-semibold">Nama Supervisor</label>
                        <input type="text"
                            name="nama_supervisor"
                            id="nama_supervisor"
                            class="form-control form-control-solid"
                            value="{{ old('nama_supervisor') }}"
                            placeholder="Nama supervisor"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>
                            <input
                                type="text"
                                name="tanggal"
                                id="tanggal"
                                class="form-control form-control-solid"
                                data-date-picker="single"
                                placeholder="YYYY-MM-DD"
                                pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                                title="Gunakan format YYYY-MM-DD"
                                autocomplete="off"
                                value="{{ old('tanggal') }}"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="jam" class="form-label fw-semibold">Jam</label>
                        <input type="time"
                            name="jam"
                            id="jam"
                            class="form-control form-control-solid"
                            value="{{ old('jam') }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="semester" class="form-label fw-semibold">Semester</label>

                        <div class="custom-select-picker" data-semester-picker data-custom-select>
                            <button type="button"
                                class="custom-select-trigger form-control text-start"
                                aria-expanded="false"
                                aria-label="Pilih semester">
                                <span class="custom-select-value">
                                    @if(old('semester') === 'Ganjil')
                                    Ganjil
                                    @elseif(old('semester') === 'Genap')
                                    Genap
                                    @else
                                    Pilih semester
                                    @endif
                                </span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </button>

                            <div class="custom-select-menu d-none" role="listbox">
                                <button type="button" class="custom-select-option {{ old('semester') === '' ? 'selected' : '' }}" data-value="" data-label="Pilih semester" disabled aria-disabled="true">
                                    <span>Pilih semester</span>
                                </button>
                                <button type="button" class="custom-select-option {{ old('semester') === 'Ganjil' ? 'selected' : '' }}" data-value="Ganjil" data-label="Ganjil">
                                    <span>Ganjil</span>
                                </button>
                                <button type="button" class="custom-select-option {{ old('semester') === 'Genap' ? 'selected' : '' }}" data-value="Genap" data-label="Genap">
                                    <span>Genap</span>
                                </button>
                            </div>

                            <input type="hidden" name="semester" value="{{ old('semester') }}" id="semester" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="surat_pemberitahuan" class="form-label fw-semibold">
                            Surat Pemberitahuan <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                            name="surat_pemberitahuan"
                            id="surat_pemberitahuan"
                            class="form-control form-control-solid"
                            accept=".pdf,application/pdf"
                            required>
                        <div class="form-text text-muted">Unggah surat pemberitahuan supervisi dalam format PDF. Wajib dilampirkan agar guru dapat melihat surat tersebut nanti.</div>
                    </div>

                    <div class="col-12">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan"
                            id="keterangan"
                            rows="4"
                            class="form-control form-control-solid"
                            placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-8">
                    <a href="{{ route('admin.supervisi.index') }}" class="btn btn-light">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check me-2"></i>
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-select-picker {
        position: relative;
    }

    .custom-select-trigger {
        display: flex !important;
        align-items: center !important;
        width: 100%;
        gap: 12px;
        background: #fff;
        color: #1f2a37;
        cursor: pointer;
        text-align: left;
        min-height: 46px;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        padding: 0.75rem 2.75rem 0.75rem 1rem;
        box-shadow: none;
    }

    .custom-select-trigger:focus {
        border-color: #b7d8ff;
        box-shadow: 0 0 0 4px rgba(40, 120, 255, 0.08);
        outline: none;
    }

    .custom-select-value {
        display: inline-block;
        min-width: 0;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #1f2a37;
    }

    .custom-select-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 1060;
        max-height: 240px;
        overflow-y: auto;
        padding: 8px;
        background: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        box-shadow: 0 12px 32px rgba(16, 24, 40, 0.12);
    }

    .custom-select-option {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 10px 12px;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #1f2a37;
        text-align: left;
        font-size: 0.92rem;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .custom-select-option:hover,
    .custom-select-option.selected {
        background: #eef5ff;
        color: #0d6efd;
    }

    .custom-select-option:disabled,
    .custom-select-option[aria-disabled="true"] {
        opacity: 0.65;
        cursor: not-allowed;
        pointer-events: none;
        background: transparent !important;
        color: #7b8093 !important;
    }

    .semester-select {
        color-scheme: light;
        -webkit-appearance: none;
        appearance: none;
        background-color: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        color: #1f2a37;
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1.5;
        min-height: 46px;
        padding: 0.75rem 2.75rem 0.75rem 1rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none'%3E%3Cpath d='M4.5 6.5L8 10L11.5 6.5' stroke='%236b7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0);
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .semester-select:focus {
        border-color: #b7d8ff;
        box-shadow: 0 0 0 4px rgba(40, 120, 255, 0.08);
        outline: none;
    }

    .custom-select-picker[data-semester-picker] .custom-select-trigger {
        min-height: 46px;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        background: #fff;
        color: #1f2a37;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0);
        padding: 0.75rem 2.75rem 0.75rem 1rem;
    }

    .custom-select-picker[data-semester-picker] .custom-select-trigger:focus,
    .custom-select-picker[data-semester-picker] .custom-select-trigger:focus-visible {
        border-color: #b7d8ff;
        box-shadow: 0 0 0 4px rgba(40, 120, 255, 0.08);
        outline: none;
    }

    .custom-select-picker[data-semester-picker] .custom-select-menu {
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        box-shadow: 0 12px 32px rgba(16, 24, 40, 0.12);
    }

    .custom-select-picker[data-semester-picker] .custom-select-option:hover,
    .custom-select-picker[data-semester-picker] .custom-select-option.selected {
        background: #eef5ff;
        color: #0d6efd;
    }

    .semester-select {
        display: none !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const guruPicker = document.querySelector('[data-guru-picker]');
        const subjectPicker = document.querySelector('[data-subject-picker]');
        const semesterPicker = document.querySelector('[data-semester-picker]');

        function parseMapels(value) {
            if (Array.isArray(value)) {
                return value.map(function(item) {
                    return String(item).trim();
                }).filter(Boolean);
            }

            if (!value) {
                return [];
            }

            if (typeof value === 'string') {
                try {
                    const parsed = JSON.parse(value);
                    if (Array.isArray(parsed)) {
                        return parsed.map(function(item) {
                            return String(item).trim();
                        }).filter(Boolean);
                    }
                } catch (error) {
                    // fallback to delimiter split below
                }

                return value.split(/[;,]/).map(function(item) {
                    return item.trim();
                }).filter(Boolean);
            }

            return [];
        }

        function setPickerValue(picker, value, label) {
            const hiddenInput = picker.querySelector('input[type="hidden"]');
            const valueLabel = picker.querySelector('.custom-select-value');
            const trigger = picker.querySelector('.custom-select-trigger');
            const menu = picker.querySelector('.custom-select-menu');
            const options = [...picker.querySelectorAll('.custom-select-option')];

            if (!hiddenInput || !valueLabel || !trigger || !menu) {
                return;
            }

            hiddenInput.value = value || '';
            valueLabel.textContent = label || 'Pilih';
            menu.classList.add('d-none');
            trigger.setAttribute('aria-expanded', 'false');

            options.forEach(function(option) {
                const isSelected = String(option.dataset.value ?? '') === String(value ?? '');
                option.classList.toggle('selected', isSelected);
            });
        }

        function initCustomSelect(picker) {
            const trigger = picker.querySelector('.custom-select-trigger');
            const menu = picker.querySelector('.custom-select-menu');

            if (!trigger || !menu) {
                return;
            }

            trigger.addEventListener('click', function(event) {
                event.stopPropagation();

                document.querySelectorAll('.custom-select-menu').forEach(function(otherMenu) {
                    if (otherMenu !== menu) {
                        otherMenu.classList.add('d-none');
                    }
                });

                document.querySelectorAll('.custom-select-trigger').forEach(function(otherTrigger) {
                    if (otherTrigger !== trigger) {
                        otherTrigger.setAttribute('aria-expanded', 'false');
                    }
                });

                const willOpen = menu.classList.contains('d-none');
                menu.classList.toggle('d-none', !willOpen);
                trigger.setAttribute('aria-expanded', String(willOpen));
            });

            menu.addEventListener('click', function(event) {
                const option = event.target.closest('.custom-select-option');
                if (!option || option.disabled || option.getAttribute('aria-disabled') === 'true') {
                    return;
                }

                const value = option.dataset.value ?? '';
                const label = option.dataset.label || option.textContent.trim();
                setPickerValue(picker, value, label);

                if (picker === guruPicker) {
                    const selectedGuruOption = guruPicker.querySelector('.custom-select-option.selected');
                    renderSubjectOptions(parseMapels(selectedGuruOption ? selectedGuruOption.dataset.mapels : ''));
                }
            });

            const hiddenInput = picker.querySelector('input[type="hidden"]');
            const currentValue = hiddenInput ? hiddenInput.value : '';
            const currentOption = [...picker.querySelectorAll('.custom-select-option')].find(function(option) {
                return String(option.dataset.value ?? '') === String(currentValue ?? '');
            });

            if (currentOption) {
                setPickerValue(picker, currentValue, currentOption.dataset.label || currentOption.textContent.trim());
            }
        }

        function renderSubjectOptions(subjects) {
            if (!subjectPicker) {
                return;
            }

            const trigger = subjectPicker.querySelector('.custom-select-trigger');
            const menu = subjectPicker.querySelector('.custom-select-menu');
            const hiddenInput = subjectPicker.querySelector('input[type="hidden"]');
            const valueLabel = subjectPicker.querySelector('.custom-select-value');

            if (!trigger || !menu || !hiddenInput || !valueLabel) {
                return;
            }

            menu.innerHTML = '';
            trigger.disabled = true;

            if (!subjects.length) {
                const placeholder = document.createElement('button');
                placeholder.type = 'button';
                placeholder.className = 'custom-select-option';
                placeholder.dataset.value = '';
                placeholder.dataset.label = 'Pilih guru terlebih dahulu';
                placeholder.disabled = true;
                placeholder.setAttribute('aria-disabled', 'true');
                placeholder.innerHTML = '<span>Pilih guru terlebih dahulu</span>';
                menu.appendChild(placeholder);
                setPickerValue(subjectPicker, '', 'Pilih guru terlebih dahulu');
                return;
            }

            trigger.disabled = false;
            const uniqueSubjects = subjects.filter(function(subject, index, array) {
                return array.indexOf(subject) === index;
            });

            uniqueSubjects.forEach(function(subject) {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'custom-select-option';
                option.dataset.value = subject;
                option.dataset.label = subject;
                option.innerHTML = '<span>' + subject + '</span>';
                menu.appendChild(option);
            });

            const selectedExisting = hiddenInput.value;
            if (selectedExisting && uniqueSubjects.includes(selectedExisting)) {
                setPickerValue(subjectPicker, selectedExisting, selectedExisting);
            } else if (uniqueSubjects.length === 1) {
                setPickerValue(subjectPicker, uniqueSubjects[0], uniqueSubjects[0]);
            } else {
                setPickerValue(subjectPicker, '', 'Pilih mata pelajaran');
            }
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.custom-select-picker')) {
                document.querySelectorAll('.custom-select-menu').forEach(function(menu) {
                    menu.classList.add('d-none');
                });

                document.querySelectorAll('.custom-select-trigger').forEach(function(trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                });
            }
        });

        if (guruPicker) {
            initCustomSelect(guruPicker);
        }

        if (subjectPicker) {
            initCustomSelect(subjectPicker);
        }

        if (semesterPicker) {
            initCustomSelect(semesterPicker);
        }

        if (guruPicker) {
            const selectedGuruOption = guruPicker.querySelector('.custom-select-option.selected');
            renderSubjectOptions(parseMapels(selectedGuruOption ? selectedGuruOption.dataset.mapels : ''));
        }
    });
</script>
@endsection