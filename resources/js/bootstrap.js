import "bootstrap";

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

import $ from "jquery";
import select2 from "select2";
import Swal from "sweetalert2";
import DataTable from "datatables.net-dt";
import "datatables.net-buttons-dt";
import "datatables.net-responsive-dt";

window.$ = $;
window.select2 = select2;
window.Swal = Swal;
window.DataTable = DataTable;
