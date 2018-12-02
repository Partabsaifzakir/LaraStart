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
            <h3 class="card-title">Vendor Visa Invoice Table</h3>

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
                  <th>Related Vendor</th>
                  <th>Invoice Date</th>
                  <th>Total Amount</th>
                  <th>Modify</th>
                </tr>

                <tr v-for="vvInvoice in vvInvoices.data" :key="vvInvoice.id">
                    <td>{{ vvInvoice.vv_invoice_no }}</td>
                    <td>{{ vvInvoice.vendor.vendor_company_name }}</td>
                    <td>{{ vvInvoice.vv_invoice_date | myDate}}</td>
                    <td>{{ formatPrice(vvInvoice.vv_invoice_grand_total) }}</td>
                  <td>
                    <a href="#" @click="editModel(vvInvoice)">
                      <i class="fas fa-edit fa-lg text-orange"></i>
                    </a>
                    &nbsp;
                    <a href="#">
                      <i class="fas fa-times fa-lg text-red"></i>
                    </a>
                    &nbsp;
                    <router-link v-bind:to="{name: 'vvInvoiceView', params: {id: vvInvoice.id}}">
                        <i class="fas fa-eye fa-lg text-blue"></i>
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
              <pagination :data="vvInvoices" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addNewVvInvoice" tabindex="-1" role="dialog" aria-labelledby="addNewVvInvoiceTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-full float-sm-right" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewVvInvoiceTitle">Add Vendor Hotel Invoice</h5>
                <h5 class="modal-title" v-show="editmode" id="updateVvInvoiceTitle">Update Vendor Hotel Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form @submit.prevent="editmode ? updateVvInvoice() : createVvInvoice()">
            <div class="modal-body">
              <div class="row">

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="vv_invoice_no" class="control-label">Invoice No.</label>
                    <input size="500" v-model="form.vv_invoice_no" type="text" name="vv_invoice_no" class="form-control" :class="{ 'is-invalid': form.errors.has('vv_invoice_no') }">
                    <has-error :form="form" field="vv_invoice_no"></has-error>
                  </div>
                </div>
                
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="vendor">Select Vendor</label>
                    <input class="form-control" type="text" list="a" v-model="form.vendor_id">
                    <datalist class="datalist" id="a">
                      <option disabled selected>Please Select Vendor</option>
                      <option v-for="vendor in selectVendors" :key="vendor.id" :value="vendor.id">{{ vendor.vendor_company_name }}</option>
                    </datalist>
                      <has-error :form="form" field="vendor_id"></has-error>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="form-group">
                  <label for="vv_invoice_date">Invoice Date</label>
                  <input v-model="form.vv_invoice_date" type="date" name="vv_invoice_date" placeholder=""
                    class="form-control" :class="{ 'is-invalid': form.errors.has('vv_invoice_date') }">
                  <has-error :form="form" field="vv_invoice_date"></has-error>
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
                  <tr v-for="(vvInvoiceItem, key) in form.vvInvoiceItems" :key="key">
                      <!--Description-->
                    <td>
                      <input size="30" v-model="vvInvoiceItem.vv_passenger_name" type="text" name="vv_passenger_name" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('vv_passenger_name') }">
                      <has-error :form="form" field="vv_passenger_name"></has-error>
                    </td>
                    <td>
                      <input size="80" v-model="vvInvoiceItem.vv_description" type="text" name="vv_description" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('vv_description') }">
                      <has-error :form="form" field="vv_description"></has-error>
                    </td>
                      <!--Fares-->
                    <td>
                      <input v-model.number="vvInvoiceItem.vv_fares" type="number" name="vv_fares"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('vv_fares') }">
                      <has-error :form="form" field="vv_fares"></has-error>
                    </td>
                    <!--Sub Total -->
                    <td>
                      <input :value="getSubTotal(key)" type="number" readonly name="vv_sub_total"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('vv_sub_total') }">
                      <has-error :form="form" field="vv_sub_total"></has-error>
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
                      <input :value="getFareTotal()" type="text" readonly name="vv_invoice_fares_total" class="form-control" :class="{ 'is-invalid': form.errors.has('vv_invoice_fares_total') }"></td> 
                    </tr>

                    <tr>
                    <td  id="borderless" class="table-empty" colspan="2"><strong></strong></td> 
                    <td  id="fillcolor" class="table-label" colspan="1"><strong>Total</strong></td>
                    <td  class="table-amount">
                      <input :value="getGrandTotal()" type="text" readonly name="vv_invoice_grand_total" class="form-control" :class="{ 'is-invalid': form.errors.has('vv_invoice_grand_total') }"></td> 
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
      editmode: true,
      vvInvoices: {},
      vendors: null,
      selectVendors: null,
      form: new Form({
        id: "",
        vendor_id: "",
        vv_invoice_no: "",
        vv_invoice_date: "",
        vv_invoice_fares_total: 0,
        vv_invoice_grand_total: 0,

        vvInvoiceItems: [
          {
            id: "",
            vv_invoice_id: "",
            vv_passenger_name: "",
            vv_description:"",
            vv_fares: 0,
            vv_sub_total: 0
          }
        ]
      })
    };
  },
  methods: {
        getResults(page = 1) {
      axios.get("api/vv-invoice?page=" + page).then(response => {
        this.vvInvoices = response.data;
      });
    },
          /*=================LOAD TICKET INVOICE CODE=================*/
    loadVvInvoices() {
      axios
        .get("api/vv-invoice")
        .then(({ data }) => (this.vvInvoices = data));
    },
    /*=================END LOAD TICKET INVOICE CODE=================*/

    /*=================LOAD VENDORS CODE=================*/
    loadVendors() {
      axios.get("api/vendor").then(({ data }) => (this.vendors = data.data));
    },

    loadSelectVendor() {
    axios
    .get("api/selectVendor")
    .then(({ data }) => (this.selectVendors = data));
    },

    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewVvInvoice").modal("show");
    },
        /*=================FORMAT MONEY CODE=================*/
    formatPrice(value) {
      let val = (value / 1).toFixed().replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //75.674,00
    },

    /*=================END FORMAT MONEY CODE=================*/
      /*==============EDIT INVOICE CODE==============*/
    editModel(vvInvoice) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewVvInvoice").modal("show");
      //  console.log("edit  => ",vvInvoice)
      this.form.fill(vvInvoice);

      this.form.vvInvoiceItems = vvInvoice.vv_invoice_items;

      // console.log("after fill  => ",this.form)
    },
    /*==============END EDIT INVOICE CODE==============*/
        /*=================ADD ITEMS FIELDS CODE=================*/
    addItems(key) {
      this.form.vvInvoiceItems.splice(key + 1, 0, {
        id: "",
        vv_invoice_id: "",
        vv_description: "",
        vv_fares: 0,
        vv_sub_total: 0
      });
    },
    /*=================END ADD ITEMS FIELDS CODE=================*/
        getSubTotal(key) {
      let calSubTotal =
        parseInt(this.form.vvInvoiceItems[key].vv_fares);

      this.form.vvInvoiceItems[key].vv_sub_total = calSubTotal;

      return calSubTotal;
    },
        getFareTotal() {
      let calFareTotal = 0;

      // console.log("1 => ", this.form);
      // console.log("2 =>", this.form.ticketInvoiceItems);//this puts  undefined why ?

      Object.values(this.form.vvInvoiceItems).forEach(
        i => (calFareTotal += parseFloat(i.vv_fares))
      );

      this.form.vv_invoice_fares_total = calFareTotal;

      return calFareTotal;
    },
        /*=================REMOVE ITEMS FIELDS CODE=================*/
    removeItems(key) {
      this.form.vvInvoiceItems.splice(key, 1);
    },
    /*=================END REMOVE ITEMS FIELDS CODE=================*/
        getGrandTotal() {
      let calGrandTotal = 0;

      calGrandTotal =
        parseInt(this.form.vv_invoice_fares_total);

      this.form.vv_invoice_grand_total = calGrandTotal;

      return calGrandTotal;
    },
        /*=================CREATE TICKET INVOICE CODE=================*/
    createVvInvoice() {
      this.$Progress.start();
      this.form
        .post("api/vv-invoice")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewVvInvoice").modal("hide");
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
        .get("api/findVVI?q=" + query)
        .then(data => {
          this.vvInvoices = data.data;
        })
        .catch();
    });

    console.log("Component mounted.");

    this.loadVvInvoices();
    Fire.$on("RefreshTable", () => {
    this.loadVvInvoices();
    });

    this.loadVendors();
    Fire.$on("RefreshTable", () => {
    this.loadVendors();
    });

    this.loadSelectVendor();
    Fire.$on("RefreshTable", () => {
    this.loadSelectVendor();
    });
  }
};
</script>
