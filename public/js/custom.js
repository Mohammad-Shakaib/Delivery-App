
$(document).ready(function() {
    user = getUser();

    if (user?.id) {
        $('.user_profile').removeClass('d-none');
        $('.user_name').text(user?.name);
    } else {
        $('.user_profile').addClass('d-none');
    }
});

function getUser() {
    let user = JSON.parse(localStorage.getItem('user'));

    return user;
}
function login() {
    let errors = '';

    let email = $('.email').val();
    let password = $('.password').val();
    const data = { email: email, password: password };

    axios.post(route('api.login', data))
        .then(function (response) {
            if (response?.data?.success) {
                localStorage.setItem("token", response?.data?.token);
                localStorage.setItem("user", JSON.stringify(response?.data?.user));
                toastr.success(response?.data?.message);
                window.location.href = route('web.homepage');
            } else {
                toastr.error(response?.data?.message);
            }
        })
        .catch(function (error) {
            if (error?.response?.status == 422) {
                error?.response?.data?.errors?.email?.map(function(error) {
                    errors += error + '<br/>';
                });
                error?.response?.data?.errors?.password?.map(function(error) {
                    errors += error + '<br/>';
                });
            } else if (error?.response?.status == 401) {
                errors = error?.response?.data?.message;
            }

            toastr.error(errors);
        });
}

function logout() {
    let errors = '';

    axios.post(route('api.logout'), {
            data: {
                // your data here
            }
        }, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        })
        .then(function (response) {
            if (response?.data?.success) {
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                toastr.success(response?.data?.message);
                window.location.href = route('web.login');
            } else {
                toastr.error(response?.data?.message);
            }

        })
        .catch(function (error) {
            errors = error?.response?.data?.message;
            if (errors) {
                toastr.error(errors);
            }
        });
}

function getParcels() {
    let errors = '';
    let parcels = '';
    let buttons = '';
    let specificRoute = '';
    user = getUser();

    if(user?.type == 'sender') {
        $('.create_parcel').removeClass('d-none')
        specificRoute = route('api.sender.parcels');
    } else if(user?.type == 'biker') {
        $('.actions').removeClass('d-none')
        $('.create_parcel').addClass('d-none')
        specificRoute = route('api.biker.parcels');
    } else {
        window.location.href = route('web.login');
    }

    axios.get(specificRoute, {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    })
        .then(function (response) {
            $.each(response?.data?.data, function (index, parcel) {
                if (user?.type == 'biker') {
                    buttons = `<td><button class="btn btn-primary btn-sm pick_up_button" onclick="parcelPickedorDelivered('${parcel.id}', 'picked')" ${parcel.status == "ready_to_deliver" ? "" : "disabled"}>Pick</button>
                        <button class="btn btn-danger btn-sm drop_of_button" onclick="parcelPickedorDelivered('${parcel.id}', 'delivered')" ${parcel.status == "picked_by_rider" ? "" : "disabled"}>Delivered</button></td>\n`;
                }
                parcels += '<tr>\n' +
                    '                    <th scope="row">'+ parcel.id +'</th>\n' +
                    '                    <td>'+ parcel.name +'</td>\n' +
                    '                    <td>'+ parcel.pick_up +'</td>\n' +
                    '                    <td>'+ parcel.drop_of +'</td>\n' +
                    '                    <td>'+ parcel.status.replaceAll('_',' ') +'</td>\n' +
                    buttons
                    '                </tr>'
            });

            if(!parcels) {
                parcels = 'No Parcels Found!';
            }

            $('.parcels').html(parcels);

        })
        .catch(function (error) {
            errors = error?.response?.data?.message;
            if (errors == 'Token has expired') {
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                toastr.error(errors);
                window.location.href = route('web.login');
            } else {
                toastr.error(errors);
            }
            console.log("error", error)

        });
}

function createParcel() {
    user = getUser();

    let errors = '';
    let name = $('.name').val();
    let pick_up = $('.pick_up').val();
    let drop_of = $('.drop_off').val();

    axios.post(route('api.create.parcel'), {
        name: name,
        pick_up: pick_up,
        drop_of: drop_of,
        sender_id: user?.id,
        status: 'ready_to_deliver'
    }, {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    })
        .then(function (response) {
            if (response?.data?.data?.success) {
                toastr.success(response?.data?.message);
                location.reload();
            } else {
                toastr.error(response?.data?.message);
            }

        })
        .catch(function (error) {
            errors = error?.response?.data?.message;
            if (errors) {
                toastr.error(errors);
            }
        });
}

function parcelPickedorDelivered(parcelId, parceltype) {
    let errors = '';
    let specificRoute = '';

    if(parceltype == 'picked') {
        specificRoute = route('api.parcel.picked');
    } else {
        specificRoute = route('api.parcel.delivered');
    }

    axios.put(specificRoute, {
        parcel_id: parcelId
    }, {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    })
        .then(function (response) {
            if (response?.data?.data?.success) {
                toastr.success(response?.data?.message);
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                toastr.error(response?.data?.message);
            }

        })
        .catch(function (error) {
            errors = error?.response?.data?.message;
            if (errors) {
                toastr.error(errors);
            }
        });
}
