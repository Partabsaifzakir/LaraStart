<style>
.modal-full {
  min-width: 86%;
  margin: auto;
}

.modal-full .modal-content {
  min-height: 100vh;
}
#borderless {
  background-color: white;
  border: none;
}
#fillcolor {
  background-color: #f2f2f2;
}
.tfoot {
  border: none;
}

.datalist {
  overflow-y: auto !important;
  height: 50px !important;
  overflow: scroll !important;
}
body {
  --table-width: 100%; /* Or any value, this will change dinamically */
}
</style>

<template>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Customer Hotel Invoice Table</h3>

            <div class="card-tools">
              <button
                class="btn btn-default"
                style="background-color: transparent;"
                @click="newModel"
              >
                <i class="fas fa-plus fa-2x text-green"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>Invoice No.</th>
                  <th>Related Customer</th>
                  <th>Invoice Date</th>
                  <th>Total Amount</th>
                  <th>Modify</th>
                </tr>

                <tr v-for="chInvoice in chInvoices.data" :key="chInvoice.id">
                  <td>{{ chInvoice.ch_invoice_no }}</td>
                  <td>{{ chInvoice.customer.customer_company_name }}</td>
                  <td>{{ chInvoice.ch_invoice_date | myDate}}</td>
                  <td>{{ formatPrice(chInvoice.ch_invoice_grand_total) }}</td>
                  <td>
                    <a href="#" @click="editModel(chInvoice)">
                      <i class="fas fa-edit fa-lg text-orange"></i>
                    </a>
                    &nbsp;
                    <a href="#">
                      <i class="fas fa-times fa-lg text-red"></i>
                    </a>
                    &nbsp;
                    <router-link v-bind:to="{name: 'chInvoiceView', params: {id: chInvoice.id}}">
                        <i class="fas fa-eye fa-lg text-blue"></i>
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="chInvoices" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="addNewChInvoice"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewChInvoiceTitle"
      aria-hidden="true"
      data-backdrop="static"
      data-keyboard="false"
    >
      <div class="modal-dialog modal-dialog-centered modal-full float-sm-right" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              v-show="!editmode"
              id="addNewChInvoiceTitle"
            >Add Customer Hotel Invoice</h5>
            <h5
              class="modal-title"
              v-show="editmode"
              id="updateChInvoiceTitle"
            >Update Customer Hotel Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editmode ? updateChInvoice() : createChInvoice()">
            <div class="modal-body">
              <div class="row">
                <!-- =====VENDOR INVOICE SELECTION===== -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="vh_invoice_no">Select Vendor Invoice</label>
                    <select
                      id="vh_invoice_no"
                      v-model="selectedHotelInvoiceId"
                      @change="getRecord"
                      name="vh_invoice_no"
                      type="text"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('vh_invoice_no')}"
                    >
                      <option
                        v-for="vhInvoice in selectVHI"
                        :key="vhInvoice.id"
                        :value="vhInvoice.id"
                      >{{ vhInvoice.vh_invoice_no }}</option>
                    </select>
                    <has-error :form="form" field="vh_invoice_no"></has-error>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="ch_invoice_no" class="control-label">Invoice No.</label>
                    <input
                      v-model="form.ch_invoice_no"
                      type="text"
                      name="ch_invoice_no"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('ch_invoice_no') }"
                    >
                    <has-error :form="form" field="ch_invoice_no"></has-error>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="customer">Select Customer</label>
                    <input class="form-control" type="text" list="a" v-model="form.customer_id">
                    <datalist class="datalist" id="a">
                      <option disabled selected>Please Select Customer</option>
                      <option
                        v-for="customer in selectCustomers"
                        :key="customer.id"
                        :value="customer.id"
                      >{{ customer.customer_company_name }}</option>
                    </datalist>
                    <has-error :form="form" field="customer_id"></has-error>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="ch_invoice_date">Invoice Date</label>
                    <input
                      v-model="form.ch_invoice_date"
                      type="date"
                      name="ch_invoice_date"
                      placeholder
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('ch_invoice_date') }"
                    >
                    <has-error :form="form" field="ch_invoice_date"></has-error>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <table
                  class="table-form table table-bordered table-responsive-md table-striped text-center"
                >
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Fares</th>
                      <th>Total</th>
                      <!--Add Button-->
                      <th>
                        <a
                          @click="addItems"
                          class="btn btn-default"
                          style="background-color: transparent;"
                        >
                          <i class="fas fa-check-circle text-green"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="(chInvoiceItem, key) in form.chInvoiceItems" :key="key">
                      <!--Description-->
                      <td>
                        <textarea
                          cols="100"
                          rows="5"
                          v-model="chInvoiceItem.ch_description"
                          type="text"
                          name="ch_description"
                          class="table-control form-control"
                          :class="{ 'is-invalid': form.errors.has('ch_description') }"
                        ></textarea>
                        <has-error :form="form" field="ch_description"></has-error>
                      </td>
                      <!--Fares-->
                      <td>
                        <input
                          v-model.number="chInvoiceItem.ch_fares"
                          type="number"
                          name="ch_fares"
                          class="table-control form-control"
                          :class="{ 'is-invalid': form.errors.has('ch_fares') }"
                        >
                        <has-error :form="form" field="ch_fares"></has-error>
                      </td>
                      <!--Sub Total -->
                      <td>
                        <input
                          :value="getSubTotal(key)"
                          type="number"
                          readonly
                          name="ch_sub_total"
                          class="table-control form-control"
                          :class="{ 'is-invalid': form.errors.has('ch_sub_total') }"
                        >
                        <has-error :form="form" field="ch_sub_total"></has-error>
                      </td>
                      <!--Remove Button-->
                      <td>
                        <a
                          v-on:click="removeItems(key)"
                          class="btn btn-default form-control"
                          style="background-color: transparent;"
                        >
                          <i class="fas fa-times-circle text-fade-red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <br>
                  <br>
                  <tfoot class="tfoot">
                    <tr>
                      <td id="borderless" class="table-empty" colspan="1">
                        <strong></strong>
                      </td>
                      <td id="fillcolor" class="table-label" colspan="1">
                        <strong>Fares</strong>
                      </td>
                      <td class="table-amount">
                        <input
                          :value="getFareTotal()"
                          type="text"
                          readonly
                          name="ch_invoice_fares_total"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('ch_invoice_fares_total') }"
                        >
                      </td>
                    </tr>

                    <tr>
                      <td id="borderless" class="table-empty" colspan="1">
                        <strong></strong>
                      </td>
                      <td id="fillcolor" class="table-label" colspan="1">
                        <strong>Total</strong>
                      </td>
                      <td class="table-amount">
                        <input
                          :value="getGrandTotal()"
                          type="text"
                          readonly
                          name="ch_invoice_grand_total"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('ch_invoice_grand_total') }"
                        >
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div
              class="modal-footer"
              style="justify-content: center; display: flex; align-items: center"
            >
              <button
                type="button"
                data-dismiss="modal"
                class="btn btn-default"
                style="background-color: transparent;"
              >
                <i class="fas fa-times-circle fa-3x text-fade-red"></i>
              </button>
              
              <button
                v-show="!editmode"
                class="btn btn-default"
                type="submit"
                style="background-color: transparent;"
              >
                <i class="fas fa-check-circle fa-3x text-green"></i>
              </button>
              
              <button
                v-show="editmode"
                class="btn btn-default"
                type="submit"
                style="background-color: transparent;"
              >
                <i class="fas fa-check-circle fa-3x text-orange"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedHotelInvoiceId: false,
      selectVHI: null,
      editmode: true,
      chInvoices: {},
      customers: null,
      selectCustomers: null,
      form: new Form({
        id: "",
        customer_id: "",
        ch_invoice_no: "",
        ch_invoice_date: "",
        ch_invoice_fares_total: 0,
        ch_invoice_grand_total: 0,

        chInvoiceItems: [
          {
            id: "",
            ch_invoice_id: "",
            ch_description: "",
            ch_fares: 0,
            ch_sub_total: 0
          }
        ]
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/ch-invoice?page=" + page).then(response => {
        this.chInvoices = response.data;
      });
    },
    loadVhInvoices() {
      axios.get("api/selectVHI").then(({ data }) => (this.selectVHI = data));
    },
    getRecord: function(e) {
      axios
        .get("api/vh-invoice/fetch/" + this.selectedHotelInvoiceId)
        .then(({ data }) => {
          console.log(data);
          this.form = new Form(data);
          // assumes the data keys maps directly to the form properties!!
          // ensure response data match the keys in the component's data.form property
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    /*=================LOAD TICKET INVOICE CODE=================*/
    loadChInvoices() {
      axios.get("api/ch-invoice").then(({ data }) => (this.chInvoices = data));
    },
    /*=================END LOAD TICKET INVOICE CODE=================*/

    /*=================LOAD VENDORS CODE=================*/
    loadCustomers() {
      axios
        .get("api/customer")
        .then(({ data }) => (this.customers = data.data));
    },

    loadSelectCustomer() {
      axios
        .get("api/selectCustomer")
        .then(({ data }) => (this.selectCustomers = data));
    },

    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewChInvoice").modal("show");
    },
    /*=================FORMAT MONEY CODE=================*/
    formatPrice(value) {
      let val = (value / 1).toFixed().replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //75.674,00
    },

    /*=================END FORMAT MONEY CODE=================*/
    /*==============EDIT INVOICE CODE==============*/
    editModel(chInvoice) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewChInvoice").modal("show");
      //  console.log("edit  => ",chInvoice)
      this.form.fill(chInvoice);

      this.form.chInvoiceItems = chInvoice.ch_invoice_items;

      // console.log("after fill  => ",this.form)
    },
    /*==============END EDIT INVOICE CODE==============*/
    /*=================ADD ITEMS FIELDS CODE=================*/
    addItems(key) {
      this.form.chInvoiceItems.splice(key + 1, 0, {
        id: "",
        ch_invoice_id: "",
        ch_description: "",
        ch_fares: 0,
        ch_sub_total: 0
      });
    },
    /*=================END ADD ITEMS FIELDS CODE=================*/
    getSubTotal(key) {
      let calSubTotal = parseInt(this.form.chInvoiceItems[key].ch_fares);

      this.form.chInvoiceItems[key].ch_sub_total = calSubTotal;

      return calSubTotal;
    },
    getFareTotal() {
      let calFareTotal = 0;

      // console.log("1 => ", this.form);
      // console.log("2 =>", this.form.vhInvoiceItems);//this puts  undefined why ?

      Object.values(this.form.chInvoiceItems).forEach(
        i => (calFareTotal += parseFloat(i.ch_fares))
      );

      this.form.ch_invoice_fares_total = calFareTotal;

      return calFareTotal;
    },
    /*=================REMOVE ITEMS FIELDS CODE=================*/
    removeItems(key) {
      this.form.chInvoiceItems.splice(key, 1);
    },
    /*=================END REMOVE ITEMS FIELDS CODE=================*/
    getGrandTotal() {
      let calGrandTotal = 0;

      calGrandTotal = parseInt(this.form.ch_invoice_fares_total);

      this.form.ch_invoice_grand_total = calGrandTotal;

      return calGrandTotal;
    },
    /*=================CREATE TICKET INVOICE CODE=================*/
    createChInvoice() {
      this.$Progress.start();
      this.form
        .post("api/ch-invoice")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewChInvoice").modal("hide");
          toast({
            type: "success",
            title: "Hotel Customer Invoice Created Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    }
    /*=================END CREATE TICKET INVOICE CODE=================*/
  },
  mounted() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findCHI?q=" + query)
        .then(data => {
          this.chInvoices = data.data;
        })
        .catch();
    });

    console.log("Component mounted.");

    this.loadChInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadChInvoices();
    });

    this.loadCustomers();
    Fire.$on("RefreshTable", () => {
      this.loadCustomers();
    });

    this.loadSelectCustomer();
    Fire.$on("RefreshTable", () => {
      this.loadSelectCustomer();
    });

    this.loadVhInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadVhInvoices();
    });
  }
};
</script>
