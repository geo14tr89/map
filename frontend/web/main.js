const qs = document.querySelector.bind(document);
const qsa = document.querySelectorAll.bind(document);

const SITENAME = 'archimapa';
const API_URL = 'https://map.transsearch.net';

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; SameSite=Lax; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

let token = getCookie('jwt_auth');

const setToken = (newToken) => {
    token = newToken;
    setCookie('jwt_auth', token, 7);
    global_data.signupComponent.render();
}

const setOnClick = (root, str, handler) => {
    const node = root.querySelector(str);
    if (!node) return;
    node.onclick = handler;
}

const objects_mock = [{
        title: 'Податкова, Черкаси',
        lat: 49.44393098181066,
        lng: 32.066114544868476,
        id: 1234,
        photos: [{
            id: '234234',
            preview_url: 'werfergf/erger_small.jpg',
            full_url: 'wfewefwf/werfwef.jpg',
            title: 'wefwefwefwef',
            user_id: 234234,
            date: '2001-01-01 00:00:00',
        }, ],
        fields: {
            date_built: '1970-01-01',
            rating: 4.5,
            status: 5,
        }
    },
    {
        title: 'Готель "Росава"',
        lat: 49.445730705669455,
        lng: 32.069161534309394,
        id: 234234,
    },
    {
        title: 'Краєзнавчий музей',
        lat: 49.44822788748563,
        lng: 32.06078231334687,
        id: 34534534,
        fields: {
            architect: 'А.Мілевський',
            date_built: '1980',
        }
    },
    {
        title: 'Палац культури',
        lat: 49.75136532385798,
        lng: 31.467705667018894,
        id: 24234234,
    },
];

const objToFormData = obj => {
    const fd = new FormData;
    for (let i in obj) {
        fd.append(i, obj[i]);
    }
    return fd;
}


class Component {
    constructor(mountNode, data) {
        if (!mountNode) {
            throw new Exception();
        }
        if (typeof mountNode === 'string') {
            mountNode = qs(mountNode);
        }
        this.mountNode = mountNode;
        this.data = data;
        this.renderedChildren = [];
    }
    name = 'Component';
    afterRender() {};
    getChildren = () => {
        //debugger;
        return [];
    };
    async render() {
        this.mountNode.innerHTML = this.getHTML();
        await Promise.all(this.getChildren().map(async(child, i) => {
            let data = {};
            if (child.getData) {
                data = await child.getData();
            }
            const Component = new child.component(this.mountNode.querySelector(child.selector), data);
            await Component.render();
            this.renderedChildren[i] = Component;
        }))
        this.afterRender();
    };
    getHTML() {
        return '<div>I\'m component</div>';
    }

    qs(str) {
        return this.mountNode.querySelector(str);
    }
    qsa(str) {
        return this.mountNode.querySelectorAll(str);
    }

    click(str, handler) {
        setOnClick(this.mountNode, str, handler);
    }
}


/*

Child: {
    getData: () => any;
    selector: string;
    component: Component;
}
*/

const commonFieldsConfig = [{
    key: 'title',
    type: 'text',
    title: 'Назва'
}];


const get_ps_fields = () => {

}

class TextFieldDisplayComponent extends Component {
    name = 'TextFieldDisplayComponent';
    getHTML = () => {
        return `
            <div>
                ${this.data.title}:
                ${this.data.value}
            </div>
        `;
    }
}

class SelectFieldDisplayComponent extends Component {
    name = 'SelectFieldDisplayComponent';
    getHTML = () => {
        console.log('........', this.data);
        const opt = this.data.options.find(o => o.value == this.data.value) || {};
        return `
            <div>
                ${this.data.title}:
                ${opt.label || this.data.value}
            </div>
        `;
    }
}

class TextFieldEditComponent extends Component {
    name = 'TextFieldEditComponent';
    getValue = () => {
        return this.mountNode.querySelector('input').value;
    }
    getHTML = () => {
        return `
            <div>
                <div>
                    ${this.data.title}
                </div>
                <div>
                    <input type="text" value="${this.data.value}" />
                </div>
            </div>
        `;
    }
}

class PasswordFieldEditComponent extends TextFieldEditComponent {
    name = 'PasswordFieldEditComponent';
    getHTML = () => {
        return `
            <div>
                <div>
                    ${this.data.title}
                </div>
                <div>
                    <input type="password" value="${this.data.value}" />
                </div>
            </div>
        `;
    }
}

class CheckboxFieldEditComponent extends Component {
    name = 'CheckboxFieldEditComponent';
    getHTML = () => {
        return `
            <div>
                <div>
                    ${this.data.title}
                </div>
                <div>
                    <input type="checkbox" value="${this.data.value}" ${this.data.value ? 'checked' : ''} />
                </div>
            </div>
        `;
    }
    getValue = () => {
        return this.mountNode.querySelector('input').checked;
    }
}

class SelectFieldEditComponent extends Component {
    name = 'SelectFieldEditComponent';
    getValue = () => {
        return this.mountNode.querySelector('select').value;
    }
    getHTML = () => {
            return `
            <div>
                <div>
                    ${this.data.title}
                </div>
                <div>
                    <select value="${this.data.value}">
                        ${this.data.options.map(o => `<option value="${o.value}" ${o.value === this.data.value ? 'selected' : ''}>${o.label}</option>`).join('')}
                    </select>
                </div>
            </div>
        `;
    }
}


const allFieldsConfig = {
    text: {
        display: TextFieldDisplayComponent,
        edit: TextFieldEditComponent,
    },
    select: {
        display: SelectFieldDisplayComponent,
        edit: SelectFieldEditComponent,
    },
}

class FieldsListComponent extends Component {
    name = 'FieldsListComponent';
    getChildren = () => {
        return this.data.fieldsConfig.map(field => {
            return {
                getData: () => {
                    return {
                        value: field.value || '',
                        title: field.title,
                        key: field.key,
                        ...field.field_data || {}
                    };
                },
                selector: '.fields-list-field-root-' + field.key,
                component: field.component,
            }
        })
    }
    getHTML = () => {
        return `
            <ul class="fields-list">
                ` + this.data.fieldsConfig.map(field => `<li class="fields-list-field-root-${field.key}"></li>`).join("") + `
            </ul>
        `;
    }
}

class FieldsEditListComponent extends FieldsListComponent {
    getValues = () => {
        const values = {};
        this.renderedChildren.forEach(child => {
            if (!child) return;
            values[child.data.key] = child.getValue();
        });
        console.log('=== FORM_VALUES_GET: ', values);
        return values;
    }
}

class MapComponent extends Component {
    name = 'MapComponent';
    getHTML = () => {
        return `
            <div class="object-map" id="object-map" style="height:400px">

            </div>
        `;
    };
    afterRender = () => {
        //console.log('MAP AFTER RENDER', this.data, qs('#object-map'));
        initMap([this.data.latitude, this.data.longitude], 'object-map');
    };
}

class GenericPopupComponent extends Component {
    getChildren = () => {
        return [{
            selector: '.some-popup-content',
            component: this.data.contentComponent,
            getData: () => {
                return {...this.data.data, close: this.hide.bind(this)};
            }
        }]
    }
    getHTML = () => {
        return `
        <div class="some-popup">

        <div class="some-popup-inside">
            <div class="some-popup-overlay">
            </div>
            <div class="some-popup-body">
                <div class="close-popup">
                    ${getl('close')}
                </div>
                <div class="some-popup-content">
                    
                </div>
            </div>
        </div>
    </div>
        `;
    }
    show = () => {
        show(this.mountNode.children[0]);
    }
    hide = () => {
        hide(this.mountNode.children[0]);
    }
    afterRender = () => {
        const confirmAndClose = () => {
            if (confirm(getl('do-you-want-to-close'))) {
                this.renderedChildren[0].onClose ? this.renderedChildren[0].onClose() : null;
                this.hide();
            }
        };
        this.mountNode.querySelector('.some-popup-overlay').onclick = confirmAndClose;
        this.mountNode.querySelector('.close-popup').onclick = confirmAndClose;
    }
}

class AddPhotoComponent extends Component {
    getHTML = () => {
        return `
        <div "class=some-popup">
            Add photo
        </div>
        `;
    }
}

class EditObjectComponent extends Component {
    getHTML = () => {
        console.log('---- <DATA>', this.data);
        return `
        <div class="edit-object-popup">
            <div class="edit-object-form">
                <h2>
                    ${getl('edit-object-form-title')}
                </h2>
                <div class="form-field">
                    <div class="form-caption">
                        Назва
                    </div>
                    <div class="form-value">
                        <input type="text" name="title" form-val value="${this.data.title}">
                    </div>
                </div>
                <div class="form-field">
                    <div class="form-caption">
                        Опис
                    </div>
                    <div class="form-value">
                        <textarea name="description" form-val>${this.data.description}</textarea>
                    </div>
                </div>
                <div class="form-field">
                    <div class="form-caption">
                        Координати
                    </div>
                    <div class="form-value">
                        <div>Широта: <input type="text" name="latitude" value="${this.data.latitude}" form-val /></div>
                        <div>Довгота: <input type="text" name="longitude" value="${this.data.longitude}" form-val /></div>
                    </div>
                </div>
                <div class="form-virtual-fields-list">
                </div>
                <div class="form-errors">

                </div>
                <div class="form-field center">
                    <button class="submit-new-object-button">
                        Додати
                    </button>
                </div>
            </div>
        </div>
        `;
    }
    afterRender = () => {
        const fieldsEdit = new FieldsEditListComponent(qs('.form-virtual-fields-list'), {
            fieldsConfig: getFieldsListConfig(this.data.fields, 'edit'),
        });
        fieldsEdit.render();

        
        qs('.submit-new-object-button').onclick = () => {
            const vals = {};
            hide(this.qs('.form-errors'));
            this.qsa('[form-val]').forEach(node => {
                const key = node.getAttribute('name');
                const val = node.value;
                vals[key] = val;
            })
            let err;
            if (!vals.title) {
                err = getl('please-enter-title');
            }
            if (!vals.latitude) {
                err = getl('please-enter-lat');
            }
            if (!vals.longitude) {
                err = getl('please-enter-lng');
            }
            if (err) {
                qs('.edit-object-form .form-errors').innerHTML = err;
                show(qs('.edit-object-form .form-errors'));
                return;
            }
            const virtualFieldsValues = fieldsEdit.getValues();
            vals.customFields = JSON.stringify(virtualFieldsValues);
            console.log('vals', vals);

            //const files = uploadImage.getFiles();
            editObject(this.data.id, vals/*, files*/).then(res => {
                global_data.showNotification(getl('object_successfully_edited'), 'success');
            }).catch(err => {
                global_data.showNotification(err, 'error');
                this.qs('.form-errors').innerHTML = err;
                show(this.qs('.form-errors'));
            });
        }
    }
}

class LoginPopupComponent extends Component {
    getHTML = () => {
        return `
        <div class="login-popup-root">
            <h3>
                ${getl('login')}
            </h3>
            <div>
                <div class="form-fields">
                </div>
                <div class="form-errors">

                </div>
                <div>   
                    <button class="submit-button">${getl('submit')}</button>
                </div>
            </div>
        </div>
        `;
    }
    afterRender = () => {
        this.mountNode.querySelector(".submit-button").onclick = () => {
            hide(this.mountNode.querySelector('.form-errors'));
            const vals = this.renderedChildren[0].getValues();
            const { username, password, rememberMe } = vals;
            let err;
            if(!username){
                err = getl('please_enter_login');
            }
            if(!password){
                err = getl('please_enter_password');
            }
            if(err){
                this.mountNode.querySelector('.form-errors').innerHTML = err;
                show(this.mountNode.querySelector('.form-errors'));
            } else {
                axios.post(API_URL + '/auth/login',
                    objToFormData({
                        username,
                        password,
                        rememberMe: Number(rememberMe)
                    })
                ).then((res) => {
                    setToken(res.data.token);
                    this.data.close();
                    showNotification(get('you_successfully_logged_in'), 'success');
                }).catch(e => {
                    show(this.mountNode.querySelector('.form-errors'));
                    this.mountNode.querySelector('.form-errors').innerHTML = e.text || getl('could_not_log_in');
                })
            }
        }
    }
    getChildren = () => {
        return [
            {
                selector: '.form-fields',
                component: FieldsEditListComponent,
                getData: () => {
                    return {
                        fieldsConfig: [
                            {
                                value: '',
                                title: getl('username'),
                                key: 'username',
                                component: TextFieldEditComponent,
                            },
                            {
                                value: '',
                                title: getl('password'),
                                key: 'password',
                                component: PasswordFieldEditComponent,
                            },
                            {
                                value: false,
                                title: getl('rememberMe'),
                                key: 'rememberMe',
                                component: CheckboxFieldEditComponent,
                            },
                        ]
                    }
                }
            }
        ]
    }
}

class SignupPopupComponent extends Component {
    getHTML = () => {
        return `
        <div class="login-popup-root">
            <h3>
                ${getl('signup')}
            </h3>
            <div>
                <div class="form-fields">
                </div>
                <div class="form-errors">

                </div>
                <div>   
                    <button class="submit-button">${getl('submit')}</button>
                </div>
            </div>
        </div>
        `;
    }
    afterRender = () => {
        this.mountNode.querySelector(".submit-button").onclick = () => {
            hide(this.mountNode.querySelector('.form-errors'));
            const vals = this.renderedChildren[0].getValues();
            const { username, password, rememberMe } = vals;
            let err;
            if(!username){
                err = getl('please_enter_login');
            }
            if(!password){
                err = getl('please_enter_password');
            }
            if(err){
                this.mountNode.querySelector('.form-errors').innerHTML = err;
                show(this.mountNode.querySelector('.form-errors'));
            } else {
                axios({
                    type: 'POST',
                    url: '/auth/login',
                    data: {
                        username,
                        password,
                        rememberMe,
                    }
                }).then(() => {
                    this.data.close();
                    showNotification(get('you_successfully_logged_in'), 'success');
                }).catch(e => {
                    show(this.mountNode.querySelector('.form-errors'));
                    this.mountNode.querySelector('.form-errors').innerHTML = e.text || getl('could_not_log_in');
                })
            }
        }
    }
    getChildren = () => {
        return [
            {
                selector: '.form-fields',
                component: FieldsEditListComponent,
                getData: () => {
                    return {
                        fieldsConfig: [
                            {
                                value: '',
                                title: getl('username'),
                                key: 'username',
                                component: TextFieldEditComponent,
                            },
                            {
                                value: '',
                                title: getl('email'),
                                key: 'email',
                                component: TextFieldEditComponent,
                            },
                            {
                                value: '',
                                title: getl('password'),
                                key: 'password',
                                component: PasswordFieldEditComponent,
                            },
                            {
                                value: '',
                                title: getl('repeat_password'),
                                key: 'repeat_password',
                                component: PasswordFieldEditComponent,
                            },
                        ]
                    }
                }
            }
        ]
    }
}

class PleaseLoginPopupComponent extends Component {
    getHTML = () => {
        return `
        <div class="please-login-popup-root">
            ${getl('please_login_or_signup_to_add_objects')}
           <div>
            <button class="login-button">
                ${getl('login')}
            </button>
            <button class="signup-button">
                ${getl('signup')}
            </button>
           </div>
        </div>
        `;
    }
    afterRender = () => {
        let l, s;
        if(l = this.mountNode.querySelector('.login-button')){
            l.onclick = () => {
                console.log('///', global_data.loginPopup);
                global_data.loginPopup ? global_data.loginPopup.show() : null;
                this.data.close();
            }
        }
        if(s = this.mountNode.querySelector('.signup-button')){
            s.onclick = () => {
                global_data.signupPopup ? global_data.signupPopup.show() : null;
                this.data.close();
            }
        }
    }
}

class SignupComponent extends Component {
    getHTML = () => {
        return `
        <div class="login-signup-root">
            ` + (!token ? 
                `<button class="login-button">
                    ${getl('login')}
                </button>
                <button class="signup-button">
                    ${getl('signup')}
                </button>` 
                : `
                <button class="logout-button">
                    ${getl('logout')}
                </button>`) + `
            <div class="login-popup">
            </div>
            <div class="signup-popup">
            </div>
        </div>
        `;
    }
    afterRender = () => {
        console.log('----------------------> AFTER RENDER');
        global_data.loginPopup = this.renderedChildren[0];
        global_data.signupPopup = this.renderedChildren[1];
        setOnClick(this.mountNode, '.login-button', () => {
            this.renderedChildren[0].show();
        })
        setOnClick(this.mountNode, '.signup-button', () => {
            this.renderedChildren[1].show();
        })
        setOnClick(this.mountNode, '.logout-button', () => {
            setToken(false);
        })
        console.log('---------------------->', this.renderedChildren);
    }
    getChildren = () => {
        return [
            {
                component: GenericPopupComponent,
                selector: '.login-popup',
                getData: () => {
                    return {
                        contentComponent: LoginPopupComponent,
                        data: {
                            close: () => {
                                this.renderedChildren[0].hide();
                            },
                        },
                    }
                }
            },
            {
                component: GenericPopupComponent,
                selector: '.signup-popup',
                getData: () => {
                    return {
                        contentComponent: SignupPopupComponent,
                        data: {
                            foo: 'bar',
                        },
                    }
                }
            },
        ]
    }
}

class ObjectPageComponent extends Component {
    name = 'ObjectPageComponent';
    getChildren = () => {
        console.log(":ObjectPageComponent getChildren");
        return [{
                component: MapComponent,
                selector: '.object-map-wrapper',
                getData: () => {
                    return this.data;
                }
            },
            {
                component: FieldsListComponent,
                selector: '.virtual-props-list',
                getData: () => {
                    const values = this.data.fields || {};
                    return {
                        fieldsConfig: getFieldsListConfig(values),
                    }
                }
            },
            {
                component: GenericPopupComponent,
                selector: '.add-photo-popup',
                getData: () => {
                    return {
                        contentComponent: AddPhotoComponent,
                        data: {
                            foo: 'bar',
                        },
                    }
                }
            },
            {
                component: GenericPopupComponent,
                selector: '.edit-object-popup',
                getData: () => {
                    return {
                        contentComponent: EditObjectComponent,
                        data: this.data,
                    }
                }
            }
        ];
    };
    getHTML = () => {
        return `
            <div class="object-page">
                <div class="add-photo-popup"></div>
                <div class="edit-object-popup"></div>
                <div>
                    <h2>${this.data.title}</h2>
                    <div>
                        <a href="#" class="add-photo-link">${getl('add_photo')}</a>
                        <a href="#" class="edit-object-link">${getl('edit_object')}</a>
                    </div>
                </div>
                <div>${this.data.description || ''}</div>
                <div class="virtual-props-list"></div>
                <div class="object-map-wrapper"></div>
            </div>
        `;
    }
    afterRender = () => {
        //console.log('MAP AFTER RENDER', this.data, qs('#object-map'));
        this.mountNode.querySelector('.add-photo-link').onclick = () => {
            this.renderedChildren[2].show();
        }
        this.mountNode.querySelector('.edit-object-link').onclick = () => {
            this.renderedChildren[3].show();
        }
    };
}

class ObjectPopupComponent extends Component {
    getHTML = () => {
        return `
            <div class="object-popup-content">
                <a href="/object.html?id=${this.data.id}"><h2>${this.data.title}</h2></a>
                <div>
                    ${this.data.description}
                </div>
                <div class="object-popup-fields-list">

                </div>
                <div class="object-popup-photos">
                    ${this.data?.images.map(i => `<a href="${API_URL + i.full_url}"><img src="${API_URL + i.preview_url}" /></a>`).join('')}
                </div>
            </div>
        `;
    }
    getChildren = () => {
        console.log('popup get data');
        return [{
            component: FieldsListComponent,
            selector: '.object-popup-fields-list',
            getData: () => {
                const values = this.data.fields || {};
                return {
                    fieldsConfig: getFieldsListConfig(values),
                }
            }
        }];
    }
    afterRender = () => {
        lightGallery(this.qs('.object-popup-photos'));
    }
}

const getFieldsListConfig = (values, type = 'display') => {
    return getSiteSpecific('objectCustomFields', []).map(field => ({
        value: values[field.key] || '',
        key: field.key,
        component: allFieldsConfig[field.type][type],
        title: field.title,
        field_data: field.field_data 
    }))
}


const global_data = {
    showNotification: (msg, type) => {
        var n = document.createElement("div");
        n.innerHTML = msg;
        n.classList.add(type);
        qs("#notifications").appendChild(n);
        setTimeout(() => {
            qs("#notifications").removeChild(n);
        }, 1000);
    },
    isLoggedIn: () => {
        console.log('token', token);
        return !!token;
    },
    markersPool: {},
};

const loadTranslations = async() => axios('/config.json').then(res => {
    global_data.texts = res.data.texts;
});

const loadSiteSpecificConfig = async() => axios('/' + SITENAME + '/config.json').then(res => {
    global_data.siteSpecificConfigs = res.data;
});

const getSiteSpecific = (key, fallback) => {
    if (!global_data.siteSpecificConfigs) {
        debugger;
    }
    return global_data.siteSpecificConfigs[key] !== undefined ? global_data.siteSpecificConfigs[key] : fallback;
}

const runSiteSpecific = (key, ...args) => {
    if(!window.site_specific_funcs || !window.site_specific_funcs[key]){
        console.error('Cannot run site specific: ' + key, window.site_specific_funcs);
        return;
    }
    const res = window.site_specific_funcs[key](...args);
    return res;
}

const fieldsConfig = [{
        key: 'date_build',
        title: 'Дата побудови',
        type: 'date',
    },
    {
        key: 'rating',
        title: 'Рейтинг',
        type: 'number',
    },
    {
        key: 'is_exist',
        title: 'Діючий',
        type: 'boolean',
    },
    {
        key: 'architect_name',
        title: 'Архітектор',
        type: 'string',
    }
];

const getUrlParam = (key) => {
    var url = new URL(window.location);
    var c = url.searchParams.get(key);
    console.log(c);
    return c;
}

const unpackObject = obj => {
    return { ...obj, fields: JSON.parse(obj.custom_fields || '{}') };
}

const loadObjectData = (object_id) => {
    console.log('loading object', object_id);
    return axios({
        method: 'get',
        url: API_URL + '/objects/' + object_id,
    }).then(res => {
        console.log('????', JSON.parse(res.data.custom_fields || '{}'));
        return unpackObject(res.data);
    }).catch(e => {
        return objects_mock.find(m => m.id == object_id);
    })
}

const loadObjects = async(data) => axios({
    method: 'get',
    url: API_URL + '/objects?' + new URLSearchParams(data).toString(),
}).then(res => {
    return res.data.map(obj => unpackObject(obj));
}).catch(e => {
    return [];
});

const show = (node) => {
    node.style.display = 'block';
}
const hide = (node) => {
    node.style.display = 'none';
}

const objectToMarker = (obj) => {
    const latlng = L.latLng(obj.latitude, obj.longitude);
    const marker = L.marker(latlng, {
        icon: runSiteSpecific('getMarkerIcon', obj),
    });
    return marker;
}

const getObjectPopup = (popup_id) => {
    return `
        <div class="object-popup-content-${popup_id}" style="width: 400px"></div>
    `;
}

let popup_ids = 0;

const loadAndDisplayMarkers = async(val) => {
    const bounds = global_data.map.getBounds();
    const params = {
        north: bounds.getNorth(),
        south: bounds.getSouth(),
        east: bounds.getEast(),
        west: bounds.getWest(),
    }
    const newObjects = await loadObjects(params);
    console.log('newObjects', newObjects);

    newObjects.forEach(obj => {
        if (!global_data.markersPool[obj.id]) {
            const marker = objectToMarker(obj);
            marker.addTo(global_data.clusterLayer);
            marker.on('click', () => {
                const popup_id = ++popup_ids;
                marker.unbindPopup().bindPopup(getObjectPopup(popup_id)).openPopup();
                const popup = new ObjectPopupComponent(qs('.object-popup-content-' + popup_id), obj);
                popup.render();
            })

            global_data.markersPool[obj.id] = obj;
        }
    })
}

class ImageUploadComponent extends Component {
    getHTML = () => {
        return `
            <div>
                <div class="form-caption">
                    Завантажити фото
                </div>
                <div class="form-value">
                    <input class="image-select" type="file" multiple />
                </div>
            </div>
        `;
    }
    getFiles() {
        return this.mountNode.querySelector('.image-select').files;
    }
}

const initMap = (initialCoords, map_id = 'map') => {
    const map = L.map(map_id).setView(initialCoords ? [initialCoords[0], initialCoords[1]] : [49.43665468061089, 32.05870628356934], 13);
    const clusterLayer = L.markerClusterGroup();
    global_data.map = map;
    global_data.clusterLayer = clusterLayer;
    L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(map);
    loadAndDisplayMarkers();
    map.on('moveend', loadAndDisplayMarkers);
    map.addLayer(clusterLayer);
    console.log('=== MAP_INITED', map, qs("#" + map_id));
}

const getl = (key) => {
    return global_data.texts[key] || key;
}

const addTranslations = async() => {
    return loadTranslations().then(() => {
        qsa('[data-tlname]').forEach(node => {
            const key = node.getAttribute('data-tlname');
            const tl = global_data.texts[key];
            node.innerHTML = tl;
        })
    })
}

const toBase64 = file => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
});

const submitNewObject = async (vals, files) => {
    const fd = new FormData();
    for (let k in vals) {
        fd.append(k, vals[k]);
    }
    for (var i in files) {
        if (files.hasOwnProperty(i)) {
            //const f = await toBase64(files[i]);
            fd.append("photo" + i, files[i]);
        }
    }
    return axios({
        method: 'post',
        url: API_URL + '/objects/add',
        data: fd,
        headers: {
            Authorization: 'Bearer ' + token,
        }
    });
}

const editObject = (id, vals) => {
    const fd = new FormData();
    for (let k in vals) {
        fd.append(k, vals[k]);
    }
    /*for (var i in files) {
        if (files.hasOwnProperty(i)) {
            fd.append("photo" + i, files[i]);
        }
    }*/
    console.log('FormData', fd);
    return axios({
        method: 'post',
        url: API_URL + '/objects/edit/' + id,
        data: fd,
        headers: {
            Authorization: 'Bearer ' + token,
        }
    });
}

const displayAddNewObjectPopup = (lat, lng) => {
    qs('.add-new-object-popup').style.display = 'block';
    qs('.add-new-object-popup-content').innerHTML = `
    
    <div class="new-object-form">
        <h2>
            ${getl('add-new-object-form-title')}
        </h2>
        <div class="form-field">
            <div class="form-caption">
                Назва
            </div>
            <div class="form-value">
                <input type="text" name="title" form-val>
            </div>
        </div>
        <div class="form-field">
            <div class="form-caption">
                Опис
            </div>
            <div class="form-value">
                <textarea name="description" form-val>

                </textarea>
            </div>
        </div>
        <div class="form-field">
            <div class="form-caption">
                Координати
            </div>
            <div class="form-value">
                <div>Широта: <input type="text" name="latitude" value="${lat}" form-val /></div>
                <div>Довгота: <input type="text" name="longitude" value="${lng}" form-val /></div>
            </div>
        </div>
        <div class="form-virtual-fields-list">
        </div>
        <div class="form-field">
            <div class="select-image-wrapper"></div>
        </div>
        <div class="form-errors">

        </div>
        <div class="form-field center">
            <button class="cancel-new-object-button">
                ${getl('cancel')}
            </button>
            <button class="submit-new-object-button">
                ${getl('add_new_object')}
            </button>
        </div>
    </div>
    
    `;

    const uploadImage = new ImageUploadComponent(qs('.select-image-wrapper'));
    uploadImage.render();

    const fieldsEdit = new FieldsEditListComponent(qs('.form-virtual-fields-list'), {
        fieldsConfig: getFieldsListConfig({}, 'edit'),
    });
    fieldsEdit.render();

    qs('.cancel-new-object-button').onclick = () => {
        if (confirm(getl('do-you-want-to-close'))) {
            hideAddNewObjectPopup();
            backFromSelectPointState();
        }
    }

    qs('.submit-new-object-button').onclick = () => {
        const vals = {};
        hide(qs('.form-errors'));
        qsa('.new-object-form [form-val]').forEach(node => {
            const key = node.getAttribute('name');
            const val = node.value;
            vals[key] = val;
        })
        let err;
        if (!vals.title) {
            err = getl('please-enter-title');
        }
        if (!vals.latitude) {
            err = getl('please-enter-lat');
        }
        if (!vals.longitude) {
            err = getl('please-enter-lng');
        }
        if (err) {
            qs('.form-errors').innerHTML = err;
            show(qs('.form-errors'));
            return;
        }
        const virtualFieldsValues = fieldsEdit.getValues();
        vals.customFields = JSON.stringify(virtualFieldsValues);
        console.log('vals', vals);

        const files = uploadImage.getFiles();
        submitNewObject(vals, files).then(res => {
            global_data.showNotification(getl('object_successfully_added'), 'success');
            hideAddNewObjectPopup();
            backFromSelectPointState();
            loadAndDisplayMarkers();
        }).catch(err => {
            global_data.showNotification(err, 'error');
            qs('.form-errors').innerHTML = err;
            show(qs('.form-errors'));
        });

    }


}
const hideAddNewObjectPopup = () => {
    qs('.add-new-object-popup').style.display = 'none';

}

const switchToSelectPointState = () => {
    qs('.please-select-point').style.display = 'block';
    qs('.add-object-button-wrapper').style.display = 'none';
    show(qs('.click-on-map-overlay'));
    qs('#map').classList.add('select-point');
    global_data.map.on('click', displayAMarkerForNewObject);
}
const backFromSelectPointState = () => {
    qs('.please-select-point').style.display = 'none';
    qs('.add-object-button-wrapper').style.display = 'block';
    qs('#map').classList.remove('select-point');
    global_data.map.off('click', displayAMarkerForNewObject);
    hide(qs('.click-on-map-overlay'));
}

let clicked = false;

const displayAMarkerForNewObject = (val) => {
    hide(qs('.click-on-map-overlay'));
    if (clicked) return;
    clicked = true;
    const latlng = L.latLng(val.latlng.lat, val.latlng.lng);
    const marker = L.marker(latlng).addTo(global_data.map);
    marker.addTo(global_data.map);
    setTimeout(() => {
        clicked = false;
        displayAddNewObjectPopup(val.latlng.lat, val.latlng.lng);
        setTimeout(() => {
            marker.removeFrom(global_data.map);
        }, 100);
    }, 500);
}

const attachAddNewObjectHandlers = (showPleaseLoginPopup) => {
    qs('.add-object-button').onclick = () => {
        if(global_data.isLoggedIn()){
            switchToSelectPointState();
        } else {
            showPleaseLoginPopup();
        }
    }

    qs('.add-new-object-popup-overlay').onclick = () => {
            if (confirm(getl('do-you-want-to-close'))) {
                hideAddNewObjectPopup();
                backFromSelectPointState();
            }
        }
        /*qs('.please-select-point').onclick = () => {
            displayAddNewObjectPopup();
            backFromSelectPointState();
        }*/
}

const initPage = async() => {
    return Promise.all([addTranslations(), loadSiteSpecificConfig()]);
}