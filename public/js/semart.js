function city_autocomplete(locale, emptyText) {
    var citySelect = $('.city-select');

    citySelect.select2({
        theme: 'bootstrap',
        language: locale
    });

    $(document).on('change', '.region-select', function () {
        var regionId = $(this).val();
        if ('' === regionId || null === regionId) {
            return;
        }

        $.ajax({
            url: Routing.generate('city_by_region', { id: regionId }),
            type: 'GET',
            data: {},
            beforeSend: function () {},
            success: function (dataResponse) {
                var cityId = $('.city-id').val();
                var options = '<option value="">' + emptyText + '</option>';

                $.each(dataResponse['cities'], function (idx, val) {
                    if (cityId === val.id) {
                        options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                    } else {
                        options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    }
                });

                citySelect.html(options);
                citySelect.select2({
                    theme: 'bootstrap',
                    language: locale
                });
            },
            error: function () {
                console.log('KO');
            }
        });
    });

    $('.region-select').change();

    $(document).on('change', '.city-select', function () {
        $('.city-id').val($(this).val());
    });
}

function department_autocomplete(locale, emptyText) {
    var departmentSelect = $('.department-select');

    departmentSelect.select2({
        theme: 'bootstrap',
        language: locale
    });

    $(document).on('change', '.company-select', function () {
        var companyId = $(this).val();
        if ('' === companyId || null === companyId) {
            return;
        }

        $.ajax({
            url: Routing.generate('department_by_company', { id: companyId }),
            type: 'GET',
            data: {},
            beforeSend: function () {},
            success: function (dataResponse) {
                var departmentId = $('.department-id').val();
                var options = '<option value="">' + emptyText + '</option>';

                $.each(dataResponse['departments'], function (idx, val) {
                    if (departmentId === val.id) {
                        options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                    } else {
                        options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    }
                });

                departmentSelect.html(options);
                departmentSelect.select2({
                    theme: 'bootstrap',
                    language: locale
                });
            },
            error: function () {
                console.log('KO');
            }
        });
    });

    $('.company-select').change();

    $(document).on('change', '.department-select', function () {
        $('.department-id').val($(this).val());
    });
}

function jobtitle_autocomplete(locale, emptyText) {
    var jobtitleSelect = $('.jobtitle-select');

    jobtitleSelect.select2({
        theme: 'bootstrap',
        language: locale
    });

    $(document).on('change', '.joblevel-select', function () {
        var joblevelId = $(this).val();
        if ('' === joblevelId || null === joblevelId) {
            return;
        }

        $.ajax({
            url: Routing.generate('jobtitle_by_joblevel', { id: joblevelId }),
            type: 'GET',
            data: {},
            beforeSend: function () {},
            success: function (dataResponse) {
                var jobtitleId = $('.jobtitle-id').val();
                var options = '<option value="">' + emptyText + '</option>';

                $.each(dataResponse['jobtitles'], function (idx, val) {
                    if (jobtitleId === val.id) {
                        options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                    } else {
                        options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    }
                });

                jobtitleSelect.html(options);
                jobtitleSelect.select2({
                    theme: 'bootstrap',
                    language: locale
                });
            },
            error: function () {
                console.log('KO');
            }
        });
    });

    $('.joblevel-select').change();

    $(document).on('change', '.jobtitle-select', function () {
        $('.jobtitle-id').val($(this).val());
    });
}

function supervisor_autocomplete(locale, emptyText) {
    var supervisorSelect = $('.supervisor-select');

    supervisorSelect.select2({
        theme: 'bootstrap',
        language: locale
    });

    $(document).on('change', '.joblevel-select', function () {
        var joblevelId = $(this).val();
        if ('' === joblevelId || null === joblevelId) {
            return;
        }

        $.ajax({
            url: Routing.generate('supervisor_by_joblevel', { id: joblevelId }),
            type: 'GET',
            data: {},
            beforeSend: function () {},
            success: function (dataResponse) {
                var supervisorId = $('.supervisor-id').val();
                var options = '<option value="">' + emptyText + '</option>';

                $.each(dataResponse['supervisors'], function (idx, val) {
                    if (supervisorId === val.id) {
                        options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.fullName + '</option>';
                    } else {
                        options += '<option value="' + val.id + '">' + val.code + ' - ' + val.fullName + '</option>';
                    }
                });

                supervisorSelect.html(options);
                supervisorSelect.select2({
                    theme: 'bootstrap',
                    language: locale
                });
            },
            error: function () {
                console.log('KO');
            }
        });
    });

    $('.joblevel-select').change();

    $(document).on('change', '.supervisor-select', function () {
        $('.supervisor-id').val($(this).val());
    });
}

function tags_autocomplete(locale) {
    var tagsSelect = $('.tags-select');
    var tagsId = $('.tags-id');
    var tags = tagsId.val().split(',');

    tagsSelect.select2({
        minimumInputLength: 2,
        theme: 'bootstrap',
        language: locale,
        tags: true,
        ajax: {
            url: Routing.generate('contract_tags'),
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.tags
                };
            }
        }
    });

    if (0 < tags.length) {
        var options = '';
        $.each(tags, function (idx, val) {
            options += '<option value="' + val + '" selected="selected">' + val + '</option>';
        });

        tagsSelect.html(options);
    }

    tagsSelect.change();

    $(document).on('change', '.tags-select', function () {
        tagsId.val($(this).val());
    });
}

function change_static_select(locale) {
    $('.static-select').select2({
        theme: 'bootstrap',
        language: locale
    });
}
