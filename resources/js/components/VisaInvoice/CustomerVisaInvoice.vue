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
            <h3 class="card-title">Customer Visa Invoice Table</h3>

            <div class="card-tools">
              <button class="btn btn-default" style="background-color: transparent;" @click="newModel">
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

                <tr v-for="cvInvoice in cvInvoices.data" :key="cvInvoice.id">
                    <td>{{ cvInvoice.cv_invoice_no }}</td>
                    <td>{{ cvInvoice.customer.customer_name }}</td>
                    <td>{{ cvInvoice.cv_invoice_date | myDate}}</td>
                    <td>{{ formatPrice(cvInvoice.cv_invoice_grand_total) }}</td>
                  <td>
                    <a href="#" @click="editModel(cvInvoice)">
                      <i class="fas fa-edit fa-lg text-orange"></i>
                    </a>
                    &nbsp;
                    <a href="#">
                      <i class="fas fa-times fa-lg text-red"></i>
                    </a>
                    &nbsp;
                    <router-link v-bind:to="{name: 'cvInvoiceView', params: {id: cvInvoice.id}}">
                        <i class="fas fa-eye fa-lg text-blue"></i>
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
              <pagination :data="cvInvoices" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addNewCvInvoice" tabindex="-1" role="dialog" aria-labelledby="addNewCvInvoiceTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-full float-sm-right" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewCvInvoiceTitle">Add Customer Hotel Invoice</h5>
                <h5 class="modal-title" v-show="editmode" id="updateCvInvoiceTitle">Update Customer Hotel Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form @submit.prevent="editmode ? updateCvInvoice() : createCvInvoice()">
            <div class="modal-body">
              <div class="row">

                <!-- =====VENDOR VISA INVOICE SELECTION===== -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="vv_invoice_no">Select Vendor Visa Invoice</label>
                    <select id="vv_invoice_no" v-model="selectedVisaInvoiceId" @change="getRecord" name="vv_invoice_no" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('vv_invoice_no')}">
                      <option v-for="vvInvoice in selectVVI" :key="vvInvoice.id" :value="vvInvoice.id">{{ vvInvoice.vv_invoice_no }}</option>
                    </select>
                      <has-error :form="form" field="vv_invoice_no"></has-error>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="cv_invoice_no" class="control-label">Invoice No.</label>
                    <input size="500" v-model="form.cv_invoice_no" type="text" name="cv_invoice_no" class="form-control" :class="{ 'is-invalid': form.errors.has('cv_invoice_no') }">
                    <has-error :form="form" field="cv_invoice_no"></has-error>
                  </div>
                </div>
                
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="customer">Select Customer</label>
                    <input class="form-control" type="text" list="a" v-model="form.customer_id">
                    <datalist class="datalist" id="a">
                      <option disabled selected>Please Select Customer</option>
                      <option v-for="customer in selectCustomers" :key="customer.id" :value="customer.id">{{ customer.customer_name }}</option>
                    </datalist>
                      <has-error :form="form" field="customer_id"></has-error>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                  <label for="cv_invoice_date">Invoice Date</label>
                  <input v-model="form.cv_invoice_date" type="date" name="cv_invoice_date" placeholder=""
                    class="form-control" :class="{ 'is-invalid': form.errors.has('cv_invoice_date') }">
                  <has-error :form="form" field="cv_invoice_date"></has-error>
                  </div>
                </div>

              </div>
              <hr>
              <div class="row">
              <table class="table-form table table-bordered table-responsive-md table-striped text-center">
                <thead>
                  <tr>
                    <th>Passenger Name</th>
                    <th>Description</th>
                    <th>Fares</th>
                    <th>Total</th>
                    <!--Add Button-->
                    <th><a @click="addItems" class="btn btn-default" style="background-color: transparent;"><i class="fas fa-check-circle text-green"></i></a></th>
                  </tr>
                </thead>
                
                <tbody>
                  <tr v-for="(cvInvoiceItem, key) in form.cvInvoiceItems" :key="key">
                      <!--Description-->
                    <td>
                      <input size="30" v-model="cvInvoiceItem.cv_passenger_name" type="text" name="cv_passenger_name" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('cv_passenger_name') }">
                      <has-error :form="form" field="cv_passenger_name"></has-error>
                    </td>
                    <td>
                      <input size="80" v-model="cvInvoiceItem.cv_description" type="text" name="cv_description" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('cv_description') }">
                      <has-error :form="form" field="cv_description"></has-error>
                    </td>
                      <!--Fares-->
                    <td>
                      <input v-model.number="cvInvoiceItem.cv_fares" type="number" name="cv_fares"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('cv_fares') }">
                      <has-error :form="form" field="cv_fares"></has-error>
                    </td>
                    <!--Sub Total -->
                    <td>
                      <input :value="getSubTotal(key)" type="number" readonly name="cv_sub_total"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('cv_sub_total') }">
                      <has-error :form="form" field="cv_sub_total"></has-error>
                    </td>
                    <!--Remove Button-->
                    <td>
                      <a v-on:click="removeItems(key)" class="btn btn-default form-control" style="background-color: transparent;"><i class="fas fa-times-circle text-fade-red"></i></a>
                    </td>
                  </tr>
                </tbody>
                <br>
                <br>
                <tfoot class="tfoot">

                    <tr>
                    <td  id="borderless" class="table-empty" colspan="2"><strong></strong></td> 
                    <td  id="fillcolor" class="table-label" colspan="1"><strong>Fares</strong></td>
                    <td  class="table-amount">
                      <input :value="getFareTotal()" type="text" readonly name="cv_invoice_fares_total" class="form-control" :class="{ 'is-invalid': form.errors.has('cv_invoice_fares_total') }"></td> 
                    </tr>

                    <tr>
                    <td  id="borderless" class="table-empty" colspan="2"><strong></strong></td> 
                    <td  id="fillcolor" class="table-label" colspan="1"><strong>Total</strong></td>
                    <td  class="table-amount">
                      <input :value="getGrandTotal()" type="text" readonly name="cv_invoice_grand_total" class="form-control" :class="{ 'is-invalid': form.errors.has('cv_invoice_grand_total') }"></td> 
                    </tr>

                </tfoot>
              </table>
              </div>
              
            </div>
        
          <div class="modal-footer" style="justify-content: center; display: flex; align-items: center">
                <button type="button" data-dismiss="modal" class="btn btn-default" style="background-color: transparent;"><i class="fas fa-times-circle fa-3x text-fade-red"></i></button>

                <button v-show="!editmode" class="btn btn-default" type="submit" style="background-color: transparent;"><i class="fas fa-check-circle fa-3x text-green"></i></button>

                <button v-show="editmode" class="btn btn-default" type="submit" style="background-color: transparent;"><i class="fas fa-check-circle fa-3x text-orange"></i></button>
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
      selectVVI: null,
      selectedVisaInvoiceId: false,
      editmode: true,
      cvInvoices: {},
      customers: null,
      selectCustomers: null,
      form: new Form({
        id: "",
        customer_id: "",
        cv_invoice_no: "",
        cv_invoice_date: "",
        cv_invoice_fares_total: 0,
        cv_invoice_grand_total: 0,

        cvInvoiceItems: [
          {
            id: "",
            cv_invoice_id: "",
            cv_passenger_name: "",
            cv_description:"",
            cv_fares: 0,
            cv_sub_total: 0
          }
        ]
      })
    };
  },
  methods: {
        getResults(page = 1) {
      axios.get("api/cv-invoice?page=" + page).then(response => {
        this.cvInvoices = response.data;
      });
    },
          /*=================LOAD TICKET INVOICE CODE=================*/
    loadCvInvoices() {
      axios
        .get("api/cv-invoice")
        .then(({ data }) => (this.cvInvoices = data));
    },
    /*=================END LOAD TICKET INVOICE CODE=================*/

    /*=================LOAD VENDORS CODE=================*/
    loadCustomers() {
      axios.get("api/customer").then(({ data }) => (this.customers = data.data));
    },

    loadSelectCustomer() {
    axios
    .get("api/selectCustomer")
    .then(({ data }) => (this.selectCustomers = data));
    },
        loadVvInvoices() {
      axios.get("api/selectVVI").then(({ data }) => (this.selectVVI = data));
    },
        getRecord: function(e) {
      axios
        .get("api/vv-invoice/fetch/" + this.selectedVisaInvoiceId)
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

    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewCvInvoice").modal("show");
    },
        /*=================FORMAT MONEY CODE=================*/
    formatPrice(value) {
      let val = (value / 1).toFixed().replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //75.674,00
    },

    /*=================END FORMAT MONEY CODE=================*/
      /*==============EDIT INVOICE CODE==============*/
    editModel(cvInvoice) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewCvInvoice").modal("show");
      //  console.log("edit  => ",cvInvoice)
      this.form.fill(cvInvoice);

      this.form.cvInvoiceItems = cvInvoice.cv_invoice_items;

      // console.log("after fill  => ",this.form)
    },
    /*==============END EDIT INVOICE CODE==============*/
        /*=================ADD ITEMS FIELDS CODE=================*/
    addItems(key) {
      this.form.cvInvoiceItems.splice(key + 1, 0, {
        id: "",
        cv_invoice_id: "",
        cv_passenger_name: "",
        cv_description: "",
        cv_fares: 0,
        cv_sub_total: 0
      });
    },
    /*=================END ADD ITEMS FIELDS CODE=================*/
        getSubTotal(key) {
      let calSubTotal =
        parseInt(this.form.cvInvoiceItems[key].cv_fares);

      this.form.cvInvoiceItems[key].cv_sub_total = calSubTotal;

      return calSubTotal;
    },
        getFareTotal() {
      let calFareTotal = 0;

      // console.log("1 => ", this.form);
      // console.log("2 =>", this.form.vvInvoiceItems);//this puts  undefined why ?

      Object.values(this.form.cvInvoiceItems).forEach(
        i => (calFareTotal += parseFloat(i.cv_fares))
      );

      this.form.cv_invoice_fares_total = calFareTotal;

      return calFareTotal;
    },
        /*=================REMOVE ITEMS FIELDS CODE=================*/
    removeItems(key) {
      this.form.cvInvoiceItems.splice(key, 1);
    },
    /*=================END REMOVE ITEMS FIELDS CODE=================*/
        getGrandTotal() {
      let calGrandTotal = 0;

      calGrandTotal =
        parseInt(this.form.cv_invoice_fares_total);

      this.form.cv_invoice_grand_total = calGrandTotal;

      return calGrandTotal;
    },
        /*=================CREATE TICKET INVOICE CODE=================*/
    createCvInvoice() {
      this.$Progress.start();
      this.form
        .post("api/cv-invoice")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewCvInvoice").modal("hide");
          toast({
            type: "success",
            title: "Hotel Invoice Created Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    },
    /*=================END CREATE TICKET INVOICE CODE=================*/
  },
  mounted() {
        Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findCVI?q=" + query)
        .then(data => {
          this.cvInvoices = data.data;
        })
        .catch();
    });

    console.log("Component mounted.");

    this.loadCvInvoices();
    Fire.$on("RefreshTable", () => {
    this.loadCvInvoices();
    });

    this.loadCustomers();
    Fire.$on("RefreshTable", () => {
    this.loadCustomers();
    });

    this.loadSelectCustomer();
    Fire.$on("RefreshTable", () => {
    this.loadSelectCustomer();
    });
        this.loadVvInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadVvInvoices();
    });
  }
};
</script>
