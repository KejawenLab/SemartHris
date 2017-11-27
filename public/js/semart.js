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

function company_autocomplete(emptyText, selected) {
    var companySelect = $('.company-select');

    $.ajax({
        url: Routing.generate('all_company'),
        type: 'GET',
        data: {},
        beforeSend: function () {},
        success: function (dataResponse) {
            var options = '<option value="">' + emptyText + '</option>';

            $.each(dataResponse['companies'], function (idx, val) {
                if (selected === val.id) {
                    options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                } else {
                    options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                }
            });

            companySelect.html(options);
            companySelect.change();
        },
        error: function () {
            console.log('KO');
        }
    });
}

function shiftment_autocomplete(emptyText, selected) {
    var shiftmentSelect = $('.shiftment-select');

    $.ajax({
        url: Routing.generate('all_shiftment'),
        type: 'GET',
        data: {},
        beforeSend: function () {},
        success: function (dataResponse) {
            var options = '<option value="">' + emptyText + '</option>';

            $.each(dataResponse['shiftments'], function (idx, val) {
                if (selected === val.id) {
                    options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                } else {
                    options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                }
            });

            shiftmentSelect.html(options);
            shiftmentSelect.change();
        },
        error: function () {
            console.log('KO');
        }
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
                    search: params.term.toUpperCase()
                };
            },
            processResults: function (data) {
                var results = [];
                $.each(data.tags, function (idx, val) {
                    results.push({
                        id: val,
                        text: val
                    });
                });

                return {
                    results: results
                };
            }
        }
    });

    if (0 < tags.length) {
        var options = '';
        $.each(tags, function (idx, val) {
            if ('' !== val) {
                options += '<option value="' + val + '" selected="selected">' + val + '</option>';
            }
        });

        tagsSelect.html(options);
    }

    tagsSelect.change();

    $(document).on('change', '.tags-select', function () {
        tagsId.val($(this).val());
    });
}

function employee_search(locale) {
    var employeeSelect = $('.employee-search');

    employeeSelect.select2({
        minimumInputLength: 2,
        theme: 'bootstrap',
        language: locale,
        cache: true,
        ajax: {
            url: Routing.generate('employee_search'),
            data: function (params) {
                return {
                    search: params.term.toUpperCase()
                };
            },
            processResults: function (data) {
                var results = [];
                $.each(data.employees, function (idx, val) {
                    results.push({
                        id: val.id,
                        text: val.code + ' - ' + val.fullName
                    });
                });

                return {
                    results: results
                };
            }
        }
    });
}

function region_search(locale) {
    var regionSelect = $('.region-search');

    regionSelect.select2({
        minimumInputLength: 2,
        theme: 'bootstrap',
        language: locale,
        cache: true,
        ajax: {
            url: Routing.generate('region_search'),
            data: function (params) {
                return {
                    search: params.term.toUpperCase()
                };
            },
            processResults: function (data) {
                var results = [];
                $.each(data.regions, function (idx, val) {
                    results.push({
                        id: val.id,
                        text: val.code + ' - ' + val.name
                    });
                });

                return {
                    results: results
                };
            }
        }
    });
}

function employee_contract_autocomplete(locale, emptyText) {
    var contractSelect = $('.contract-select');

    $.ajax({
        url: Routing.generate('contract_employee'),
        type: 'GET',
        data: {},
        beforeSend: function () {},
        success: function (dataResponse) {
            var contractId = $('.contract-id').val();
            var options = '<option value="">' + emptyText + '</option>';

            $.each(dataResponse['contracts'], function (idx, val) {
                if (contractId === val.id) {
                    options += '<option value="' + val.id + '" selected="selected">' + val.letterNumber + ' - ' + val.subject + '</option>';
                } else {
                    options += '<option value="' + val.id + '">' + val.letterNumber + ' - ' + val.subject + '</option>';
                }
            });

            contractSelect.html(options);
            contractSelect.select2({
                theme: 'bootstrap',
                language: locale
            });
        },
        error: function () {
            console.log('KO');
        }
    });

    $(document).on('change', '.contract-select', function () {
        $('.contract-id').val($(this).val());
    });
}

function fixed_component_autocomplete(locale, emptyText) {
    var componentSelect = $('.component-select');

    $.ajax({
        url: Routing.generate('fixed_component'),
        type: 'GET',
        data: {},
        beforeSend: function () {},
        success: function (dataResponse) {
            var componentId = $('.component-id').val();
            var options = '<option value="">' + emptyText + '</option>';

            $.each(dataResponse['components'], function (idx, val) {
                if (componentId === val.id) {
                    options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                } else {
                    options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                }
            });

            componentSelect.html(options);
            componentSelect.select2({
                theme: 'bootstrap',
                language: locale
            });
        },
        error: function () {
            console.log('KO');
        }
    });

    $(document).on('change', '.component-select', function () {
        $('.component-id').val($(this).val());
    });
}


function salary_component_autocomplete(state, locale, emptyText) {
    var componentSelect = $('.component-select');

    $.ajax({
        url: Routing.generate('salary_component'),
        type: 'GET',
        data: {
            state: state
        },
        beforeSend: function () {},
        success: function (dataResponse) {
            var componentId = $('.component-id').val();
            var options = '<option value="">' + emptyText + '</option>';

            $.each(dataResponse['components'], function (idx, val) {
                if (componentId === val.id) {
                    options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                } else {
                    options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                }
            });

            componentSelect.html(options);
            componentSelect.select2({
                theme: 'bootstrap',
                language: locale
            });
        },
        error: function () {
            console.log('KO');
        }
    });

    $(document).on('change', '.component-select', function () {
        $('.component-id').val($(this).val());
    });
}

function reason_autocomplete(locale, type, emptyText) {
    var reasonSelect = $('.reason-select');

    $.ajax({
        url: Routing.generate('reason_by_type', { type: type }),
        type: 'GET',
        data: {},
        beforeSend: function () {},
        success: function (dataResponse) {
            var reasonId = $('.reason-id').val();
            var options = '<option value="">' + emptyText + '</option>';

            $.each(dataResponse['reasons'], function (idx, val) {
                if (reasonId === val.id) {
                    options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
                } else {
                    options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                }
            });

            reasonSelect.html(options);
            reasonSelect.select2({
                theme: 'bootstrap',
                language: locale
            });
        },
        error: function () {
            console.log('KO');
        }
    });

    $(document).on('change', '.reason-select', function () {
        $('.reason-id').val($(this).val());
    });
}

function find_employee(id) {
    $.ajax({
        url: Routing.generate('employee_get_for_tax_change', { id: id }),
        type: 'GET',
        data: {},
        beforeSend: function () {},
        success: function (dataResponse) {
            var employee = dataResponse['employee'];
            if (null !== employee) {
                $('.tax-group').val(employee.tax_group);
                $('.risk-ratio').val(employee.risk_ratio);
            }
        },
        error: function () {
            console.log('KO');
        }
    });
}

function change_static_select(locale) {
    $('.static-select').select2({
        theme: 'bootstrap',
        language: locale
    });
}

function date_picker(locale) {
    $('.date-picker').datepicker({
        format: 'dd-mm-yyyy',
        language: locale,
        todayBtn: true,
        todayHighlight: true,
        weekStart: 1,
        autoclose: true
    });
}

function date_range_picker(locale, format) {
    $('.input-daterange').datepicker({
        format: format,
        language: locale,
        todayBtn: true,
        todayHighlight: true,
        weekStart: 1,
        autoclose: true
    });
}

function time_picker() {
    $('.time-picker').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Pilih'
    });
}

function change_file_chooser(btnText, iconCss, input) {
    if ('undefined' === typeof input) {
        input = false;
    }

    $(':file').filestyle({
        input: input,
        buttonBefore: true,
        buttonText: btnText,
        iconName: iconCss,
        badge: true
    });
}

function createDate(input, format) {
    var parts = input.match(/(\d+)/g);
    var i = 0;
    var fmt = {};

    format.replace(/(yyyy|dd|mm)/g, function(part) { fmt[part] = i++; });

    return new Date(parts[fmt['yyyy']], parts[fmt['mm']]-1, parts[fmt['dd']]);
}

function __numeric() {
    $('.numeric').numeric();
}

function __positive() {
    $('.positive').numeric({ negative: false }, function() { this.value = ''; this.focus(); });
}

Array.prototype.unique = function() {
    return this.filter(function (value, index, self) {
        return self.indexOf(value) === index;
    });
};
