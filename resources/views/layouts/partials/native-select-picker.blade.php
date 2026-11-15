<script>
    document.addEventListener('DOMContentLoaded', function() {
        let generatedId = 0;

        function closePickers(except) {
            document.querySelectorAll('.custom-select-picker').forEach(function(picker) {
                if (picker === except) {
                    return;
                }

                const menu = picker.querySelector('.custom-select-menu');
                const trigger = picker.querySelector('.custom-select-trigger');

                if (menu) {
                    menu.classList.add('d-none');
                }

                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        }

        function normaliseSearchText(value) {
            return String(value || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLocaleLowerCase('id');
        }

        function applyPickerSearch(menu, query) {
            const normalizedQuery = normaliseSearchText(query).trim();
            const options = Array.from(menu.querySelectorAll('.custom-select-option'));
            const headings = Array.from(menu.querySelectorAll('.custom-select-group-label'));
            let visibleOptionCount = 0;

            headings.forEach(function(heading) {
                heading.classList.toggle('d-none', Boolean(normalizedQuery));
            });

            options.forEach(function(option) {
                const optionText = option.dataset.label || option.textContent.trim();
                const matches = !normalizedQuery || (
                    !option.disabled && normaliseSearchText(optionText).includes(normalizedQuery)
                );

                option.classList.toggle('d-none', !matches);

                if (!option.disabled && matches) {
                    visibleOptionCount += 1;

                    let previous = option.previousElementSibling;
                    while (previous && !previous.classList.contains('custom-select-group-label')) {
                        previous = previous.previousElementSibling;
                    }

                    if (previous) {
                        previous.classList.remove('d-none');
                    }
                }
            });

            const emptyState = menu.querySelector('.custom-select-empty');
            if (emptyState) {
                emptyState.classList.toggle('d-none', !normalizedQuery || visibleOptionCount > 0);
            }
        }

        function enhancePickerSearch(picker) {
            const menu = picker.querySelector('.custom-select-menu');
            const trigger = picker.querySelector('.custom-select-trigger');
            const valueField = picker.querySelector('select, input[type="hidden"]');
            const fieldName = valueField ? (valueField.name || valueField.id || '').toLowerCase() : '';

            if (
                !menu
                || !trigger
                || picker.classList.contains('siguru-compact-select-picker')
                || fieldName === 'kelas'
            ) {
                return;
            }

            function refreshSearch() {
                const options = Array.from(menu.querySelectorAll('.custom-select-option'));
                const searchableCount = options.filter(function(option) {
                    const optionValue = option.dataset.value !== undefined
                        ? option.dataset.value
                        : option.textContent.trim();
                    return !option.disabled && String(optionValue).trim() !== '';
                }).length;
                let searchInput = menu.querySelector('.custom-select-search');
                let emptyState = menu.querySelector('.custom-select-empty');
                let optionsList = menu.querySelector('.siguru-custom-select-options');

                if (!menu.id) {
                    menu.id = 'siguru-picker-menu-' + (++generatedId);
                }

                if (searchableCount <= 2) {
                    if (optionsList) {
                        Array.from(optionsList.children).forEach(function(child) {
                            menu.insertBefore(child, optionsList);
                        });
                        optionsList.remove();
                    }

                    if (searchInput) searchInput.remove();
                    if (emptyState) emptyState.remove();
                    menu.classList.remove('siguru-searchable-select-menu');
                    menu.setAttribute('role', 'listbox');
                    trigger.setAttribute('aria-haspopup', 'listbox');
                    trigger.setAttribute('aria-controls', menu.id);
                    options.forEach(function(option) { option.classList.remove('d-none'); });
                    menu.querySelectorAll('.custom-select-group-label').forEach(function(heading) {
                        heading.classList.remove('d-none');
                    });
                    return;
                }

                if (!optionsList) {
                    optionsList = document.createElement('div');
                    optionsList.className = 'siguru-custom-select-options';
                    optionsList.id = menu.id + '-options';
                    optionsList.setAttribute('role', 'listbox');

                    Array.from(menu.children).forEach(function(child) {
                        if (
                            !child.classList.contains('custom-select-search')
                            && !child.classList.contains('custom-select-empty')
                            && !child.classList.contains('siguru-custom-select-options')
                        ) {
                            optionsList.appendChild(child);
                        }
                    });

                    menu.appendChild(optionsList);
                }

                if (!searchInput) {
                    searchInput = document.createElement('input');
                    searchInput.type = 'search';
                    searchInput.className = 'custom-select-search';
                    searchInput.placeholder = 'Cari pilihan...';
                    searchInput.setAttribute('aria-label', 'Cari ' + (trigger.getAttribute('aria-label') || 'pilihan'));
                    searchInput.addEventListener('input', function() {
                        applyPickerSearch(menu, searchInput.value);
                    });
                    searchInput.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape') {
                            event.preventDefault();
                            event.stopPropagation();
                            menu.classList.add('d-none');
                            trigger.setAttribute('aria-expanded', 'false');
                            trigger.focus();
                        } else if (event.key === 'ArrowDown') {
                            const firstOption = menu.querySelector('.custom-select-option:not(:disabled):not(.d-none)');
                            if (firstOption) {
                                event.preventDefault();
                                event.stopPropagation();
                                firstOption.focus();
                            }
                        }
                    });
                    menu.insertBefore(searchInput, optionsList);
                }

                if (!emptyState) {
                    emptyState = document.createElement('div');
                    emptyState.className = 'custom-select-empty d-none';
                    emptyState.textContent = 'Tidak ada hasil yang cocok.';
                    emptyState.setAttribute('role', 'status');
                    menu.appendChild(emptyState);
                }

                optionsList.setAttribute('aria-label', trigger.getAttribute('aria-label') || 'Pilihan');
                menu.classList.add('siguru-searchable-select-menu');
                menu.setAttribute('role', 'dialog');
                menu.setAttribute('aria-label', 'Cari dan pilih opsi');
                trigger.setAttribute('aria-haspopup', 'dialog');
                trigger.setAttribute('aria-controls', optionsList.id);

                Array.from(menu.children).forEach(function(child) {
                    if (
                        child !== searchInput
                        && child !== emptyState
                        && child !== optionsList
                    ) {
                        optionsList.appendChild(child);
                    }
                });

                applyPickerSearch(menu, searchInput.value);

                if (!menu.dataset.siguruSearchHandlers) {
                    menu.dataset.siguruSearchHandlers = 'true';

                    menu.addEventListener('click', function(event) {
                        if (!event.target.closest('.custom-select-option')) return;

                        const currentSearch = menu.querySelector('.custom-select-search');
                        if (currentSearch) {
                            currentSearch.value = '';
                            applyPickerSearch(menu, '');
                        }
                    });

                    trigger.addEventListener('click', function() {
                        const currentSearch = menu.querySelector('.custom-select-search');
                        if (currentSearch) {
                            currentSearch.value = '';
                            applyPickerSearch(menu, '');
                            window.requestAnimationFrame(function() {
                                if (!menu.classList.contains('d-none')) {
                                    currentSearch.focus();
                                }
                            });
                        }
                    });
                }
            }

            refreshSearch();

            if (!picker.dataset.siguruSearchObserver && window.MutationObserver) {
                picker.dataset.siguruSearchObserver = 'true';
                const observer = new MutationObserver(refreshSearch);
                observer.observe(menu, { childList: true, subtree: true });
            }
        }

        function enhanceSelect(select) {
            if (
                select.multiple
                || select.hasAttribute('size')
                || select.closest('.custom-select-picker')
                || select.classList.contains('select2-hidden-accessible')
            ) {
                return;
            }

            const labels = Array.from(select.labels || []);
            const labelText = labels
                .map(function(label) { return label.textContent.trim(); })
                .filter(Boolean)
                .join(' ');
            select.dataset.siguruLabel = labelText;
            const selectId = select.id || 'siguru-select-' + (++generatedId);
            const triggerId = selectId + '-picker-trigger';
            const menuId = selectId + '-picker-menu';
            const isCompactStatus = select.classList.contains('status-select');
            const required = select.required;
            const wrapper = document.createElement('div');
            const trigger = document.createElement('button');
            const value = document.createElement('span');
            const arrow = document.createElement('i');
            const menu = document.createElement('div');
            const feedback = document.createElement('div');

            wrapper.className = 'custom-select-picker siguru-custom-select-picker';
            wrapper.style.width = isCompactStatus ? 'auto' : '100%';

            if (isCompactStatus) {
                wrapper.classList.add('siguru-compact-select-picker');
                wrapper.style.display = 'inline-block';
            }

            trigger.type = 'button';
            trigger.id = triggerId;
            trigger.className = 'custom-select-trigger text-start';

            if (isCompactStatus) {
                trigger.classList.add('siguru-status-select-trigger');
                trigger.style.backgroundImage = 'none';
            }

            if (!isCompactStatus) {
                trigger.classList.add('form-control');
            }

            Array.from(select.classList).forEach(function(className) {
                if (!['form-select', 'form-select-solid', 'form-control'].includes(className)) {
                    trigger.classList.add(className);
                }
            });

            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.setAttribute('aria-controls', menuId);

            if (select.disabled) {
                trigger.disabled = true;
            }

            const accessibleLabel = select.getAttribute('aria-label') || labelText || 'Pilih opsi';
            trigger.setAttribute('aria-label', accessibleLabel);

            value.className = 'custom-select-value';
            arrow.className = 'bi bi-chevron-down ms-auto';
            arrow.setAttribute('aria-hidden', 'true');
            trigger.append(value, arrow);

            menu.id = menuId;
            menu.className = 'custom-select-menu d-none';
            menu.setAttribute('role', 'listbox');
            menu.setAttribute('aria-label', accessibleLabel);

            feedback.className = 'siguru-select-error d-none';
            feedback.setAttribute('role', 'alert');

            labels.forEach(function(label) {
                if (!label.id) {
                    label.id = selectId + '-picker-label';
                }

                label.htmlFor = triggerId;
            });

            trigger.setAttribute('aria-labelledby', labels.map(function(label) {
                return label.id;
            }).join(' '));

            const pickerOptions = [];
            Array.from(select.options).forEach(function(option) {
                const group = option.parentElement && option.parentElement.tagName === 'OPTGROUP'
                    ? option.parentElement
                    : null;

                if (group && !pickerOptions.some(function(item) { return item.group === group; })) {
                    pickerOptions.push({ group: group, label: group.label });
                }

                pickerOptions.push({ option: option, group: group });
            });

            let lastGroup = null;
            pickerOptions.forEach(function(item) {
                if (item.group && item.group !== lastGroup) {
                    const heading = document.createElement('div');
                    heading.className = 'custom-select-group-label';
                    heading.textContent = item.label || item.group.label;
                    menu.appendChild(heading);
                    lastGroup = item.group;
                } else if (!item.group) {
                    lastGroup = null;
                }

                if (!item.option) {
                    return;
                }

                const optionButton = document.createElement('button');
                optionButton.type = 'button';
                optionButton.className = 'custom-select-option';
                optionButton.textContent = item.option.textContent.trim();
                optionButton.dataset.value = item.option.value;
                optionButton.setAttribute('role', 'option');
                optionButton.setAttribute('aria-selected', String(item.option.selected));
                optionButton.disabled = item.option.disabled || (item.group && item.group.disabled);

                if (optionButton.disabled) {
                    optionButton.setAttribute('aria-disabled', 'true');
                }

                optionButton.addEventListener('click', function() {
                    if (optionButton.disabled) {
                        return;
                    }

                    select.value = item.option.value;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    closePickers();
                    trigger.focus();
                });

                menu.appendChild(optionButton);
            });

            function syncValue() {
                const selectedOption = select.options[select.selectedIndex];
                value.textContent = selectedOption ? selectedOption.textContent.trim() : '';

                menu.querySelectorAll('.custom-select-option').forEach(function(optionButton) {
                    const selected = optionButton.dataset.value === select.value;
                    optionButton.classList.toggle('selected', selected);
                    optionButton.setAttribute('aria-selected', String(selected));
                });

                const error = wrapper.querySelector('.siguru-select-error');
                if (error) {
                    error.classList.add('d-none');
                    error.textContent = '';
                }

                trigger.classList.remove('is-invalid');
                trigger.removeAttribute('aria-invalid');
                trigger.disabled = select.disabled;
            }

            function openMenu(focusSelected) {
                closePickers(wrapper);
                menu.classList.remove('d-none');
                trigger.setAttribute('aria-expanded', 'true');

                if (focusSelected) {
                    const searchInput = menu.querySelector('.custom-select-search');
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
                        searchInput.focus();
                        return;
                    }

                    const selectedOption = menu.querySelector('.custom-select-option.selected:not(:disabled)');
                    const firstOption = menu.querySelector('.custom-select-option:not(:disabled)');
                    (selectedOption || firstOption || trigger).focus();
                }
            }

            trigger.addEventListener('click', function() {
                if (menu.classList.contains('d-none')) {
                    openMenu(false);
                } else {
                    menu.classList.add('d-none');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });

            trigger.addEventListener('keydown', function(event) {
                if (['ArrowDown', 'ArrowUp'].includes(event.key)) {
                    event.preventDefault();
                    openMenu(true);
                } else if (event.key === 'Escape') {
                    menu.classList.add('d-none');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });

            menu.addEventListener('keydown', function(event) {
                const availableOptions = Array.from(
                    menu.querySelectorAll('.custom-select-option:not(:disabled):not(.d-none)')
                );
                const currentIndex = availableOptions.indexOf(document.activeElement);

                if (event.key === 'Escape') {
                    event.preventDefault();
                    menu.classList.add('d-none');
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.focus();
                } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    const offset = event.key === 'ArrowDown' ? 1 : -1;
                    const nextIndex = Math.max(0, Math.min(availableOptions.length - 1, currentIndex + offset));
                    if (availableOptions[nextIndex]) {
                        availableOptions[nextIndex].focus();
                    }
                } else if (event.key === 'Home' && availableOptions.length) {
                    event.preventDefault();
                    availableOptions[0].focus();
                } else if (event.key === 'End' && availableOptions.length) {
                    event.preventDefault();
                    availableOptions[availableOptions.length - 1].focus();
                }
            });

            select.dataset.siguruRequired = required ? 'true' : 'false';
            select.required = false;
            select.tabIndex = -1;
            select.setAttribute('aria-hidden', 'true');
            select.style.display = 'none';
            select.addEventListener('change', syncValue);

            const parent = select.parentNode;
            parent.insertBefore(wrapper, select);
            wrapper.append(select, trigger, menu, feedback);

            if (required && select.form) {
                select.form.addEventListener('submit', function(event) {
                    const missing = Array.from(select.form.querySelectorAll('select[data-siguru-required="true"]'))
                        .find(function(requiredSelect) {
                            return !requiredSelect.disabled && requiredSelect.value === '';
                        });

                    if (!missing) {
                        return;
                    }

                    event.preventDefault();
                    event.stopImmediatePropagation();

                    const missingPicker = missing.closest('.siguru-custom-select-picker');
                    const missingTrigger = missingPicker.querySelector('.custom-select-trigger');
                    const missingFeedback = missingPicker.querySelector('.siguru-select-error');
                    const label = missing.dataset.siguruLabel || 'Pilihan';

                    missingTrigger.classList.add('is-invalid');
                    missingTrigger.setAttribute('aria-invalid', 'true');
                    missingFeedback.textContent = 'Pilih ' + label.replace(/\s*\*\s*$/, '').toLowerCase() + ' terlebih dahulu.';
                    missingFeedback.classList.remove('d-none');
                    missingTrigger.focus();
                    missingTrigger.click();
                }, true);
            }

            if (select.form) {
                select.form.addEventListener('reset', function() {
                    window.setTimeout(syncValue, 0);
                });
            }

            syncValue();
        }

        document.querySelectorAll('select:not([multiple]):not([size])').forEach(enhanceSelect);
        document.querySelectorAll('.custom-select-picker').forEach(enhancePickerSearch);

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.custom-select-picker')) {
                closePickers();
            }
        });
    });
</script>
