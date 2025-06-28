function previewImage(event, id) {
    const file = event.target.files[0];
    if (file) {
        const img = document.getElementById(id);
        const objectUrl = URL.createObjectURL(file);
        img.src = objectUrl;
        img.onload = () => URL.revokeObjectURL(objectUrl);
    }
}

function deleteConfirmation(event, id) {
    event.preventDefault();
    const alertButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
        },
        buttonsStyling: false
    });

    alertButtons.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

function setupDependentSelect(parentSelector, childSelector, fetchUrlTemplate, optionMapper = null, defaultOptionText = 'Select an option') {
    const parentSelect = document.querySelector(parentSelector);
    const childSelect = document.querySelector(childSelector);

    if (!parentSelect || !childSelect) return;

    parentSelect.addEventListener('change', function () {
        const parentId = this.value;
        childSelect.innerHTML = '<option value="">Loading...</option>';

        if (parentId) {
            const url = fetchUrlTemplate.replace(':id', parentId);

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    childSelect.innerHTML = `<option value="">${defaultOptionText}</option>`;
                    data.forEach(item => {
                        const option = document.createElement('option');
                        if (optionMapper && typeof optionMapper === 'function') {
                            const mapped = optionMapper(item);
                            option.value = mapped.value;
                            option.textContent = mapped.text;
                        } else {
                            option.value = item.id;
                            option.textContent = item.name;
                        }
                        childSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error(error);
                    childSelect.innerHTML = '<option value="">Failed to load options</option>';
                });
        } else {
            childSelect.innerHTML = `<option value="">${defaultOptionText}</option>`;
        }
    });
}