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
</style>
<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer Invoice Table</h3>

                <div class="card-tools">
                    <a class="btn btn-default" style="background-color: transparent;" @click="newModel">
                        <i class="fas fa-user-plus fa-2x text-green"></i>
                    </a>       
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
                  
                  <tr v-for="ctInvoice in ctInvoices.data" :key="ctInvoice.id">
                    <td>{{ ctInvoice.ct_invoice_no }}</td>
                    <td>{{ ctInvoice.customer.customer_name }}</td>
                    <td>{{ ctInvoice.ct_invoice_date | myDate }}</td>
                    <td>{{ formatPrice(ctInvoice.ct_invoice_grand_total) }}</td>
                    <td>
                        <a href="#" @click="editModel(ctInvoice)">
                            <i class="fas fa-user-edit fa-lg text-orange"></i>
                        </a>
                        &nbsp;
                        <a href="#">
                            <i class="fas fa-user-times fa-lg text-red"></i>
                        </a>
                        &nbsp;
                        <router-link v-bind:to="{name: 'ctInvoiceView', params: {id: ctInvoice.id}}">
                            <i class="fas fa-eye fa-lg text-blue"></i>
                        </router-link>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="ctInvoices" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            </div>
        </div>
    <div class="modal fade" id="addNewCtInvoice" tabindex="-1" role="dialog" aria-labelledby="addNewCtInvoiceTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-full float-sm-right" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewCtInvoiceTitle">Add Customers Invoice</h5>
                <h5 class="modal-title" v-show="editmode" id="updateCtInvoiceTitle">Update Customer's Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form @submit.prevent="editmode ? updateCtInvoice() : createCtInvoice()">
            <div class="modal-body">
              <div class="row">

                <!-- =====VENDOR INVOICE SELECTION===== -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="ticket_invoice_no">Select Vendor Invoice</label>
                    <select id="ticket_invoice_no" v-model="selectedTicketInvoiceId" @change="getRecord" name="ticket_invoice_no" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_no')}">
                      <option v-for="ticketInvoice in ticketInvoices" :key="ticketInvoice.id" :value="ticketInvoice.id">{{ ticketInvoice.ticket_invoice_no }}</option>
                    </select>
                      <has-error :form="form" field="ticket_invoice_no"></has-error>
                  </div>
                </div>

                <!-- =====CUSTOMER TICKET INVOICE NUMBER===== -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="ct_invoice_no" class="control-label">Invoice No.</label>
                    <input v-model="form.ct_invoice_no" type="text" name="ct_invoice_no" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_no') }">
                    <has-error :form="form" field="ct_invoice_no"></has-error>
                  </div>
                </div>
                
                <!-- =====CUSTOMER SELECTION===== -->
                <div class="col-sm-2">
                  <div class="form-group">

                    <label for="customer">Select Customer</label>
                    <select id="customer_id" name="customer_id" v-model="form.customer_id" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('customer_id')}">
                      <option disabled selected>Please Select Customer</option>
                      <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.customer_name }}</option>
                    </select>
                      <has-error :form="form" field="customer_id"></has-error>
                  </div>
                </div>

                <!-- =====CUSTOMER INVOICE DATE===== -->
                <div class="col-sm-2">
                  <div class="form-group">
                  <label for="ct_invoice_date">Invoice Date</label>
                  <input v-model="form.ct_invoice_date" type="date" name="ct_invoice_date"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_date') }">
                  <has-error :form="form" field="ct_invoice_date"></has-error>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
              <table class="table-form table table-bordered table-responsive-md table-striped text-center">
                <thead>
                  <tr>
                    <th>Passenger Name</th>
                    <th>Ticket No</th>
                    <th>Flight No</th>
                    <th>Departure Date</th>
                    <th>Sector</th>
                    <th>Tax BreakUp</th>
                    <th>Taxes Total</th>
                    <th>Fares</th>
                    <th>Total</th>
                    <!--Add Button-->
                    <th><a @click="addItems" class="btn btn-default" style="background-color: transparent;"><i class="fas fa-check-circle text-green"></i></a></th>
                  </tr>
                </thead>
                
                <tbody>
                  <tr v-for="(ctInvoiceItem, key) in form.ctInvoiceItems" :key="key">
                      <!--Passenger Name-->
                    <td>
                      <input v-model="ctInvoiceItem.ct_passenger_name" size="40" type="text" name="ct_passenger_name" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_passenger_name') }">
                      <has-error :form="form" field="ct_passenger_name"></has-error>
                    </td>
                    <!--Ticket No.-->
                    <td>
                      <input v-model="ctInvoiceItem.ct_ticket_no" size="24" type="text" name="ct_ticket_no" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_ticket_no') }">
                      <has-error :form="form" field="ct_ticket_no"></has-error>
                    </td>
                    <!--Flight No.-->
                    <td>
                      <input v-model="ctInvoiceItem.ct_flight_no" size="7" type="text" name="ct_flight_no" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_flight_no') }">
                      <has-error :form="form" field="ct_flight_no"></has-error>
                    </td>
                    <!--Departure Date-->
                    <td>
                      <input v-model="ctInvoiceItem.ct_departure_date" type="date" name="ct_departure_date" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_departure_date') }">
                      <has-error :form="form" field="ct_departure_date"></has-error>
                    </td>
                    <!--Sector-->
                    <td>
                      <input v-model="ctInvoiceItem.ct_sector" type="text" name="ct_sector" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_sector') }">
                      <has-error :form="form" field="ct_sector"></has-error>
                    </td>
                    <!--DROPDOWN MENU-->
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu form-group" aria-labelledby="dropdownMenuButton">
                        
                          <!--============Taxes BreakUp============-->
                            
                            <!--===== ct_tax_SB =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_SB" id="ct_tax_SB" type="number" name="ct_tax_SB" placeholder="SB"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_SB') }">
                            <has-error :form="form" field="ct_tax_SB"></has-error>
                            

                            <!--===== ct_tax_SRP =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_SRP" id="ct_tax_SRP" type="number" name="ct_tax_SRP" placeholder="SRP"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_SRP') }">
                            <has-error :form="form" field="ct_tax_SRP"></has-error>
                            
                            <!--===== ct_tax_YQ =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_YQ" id="ct_tax_YQ" type="number" name="ct_tax_YQ" placeholder="YQ"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_YQ') }">
                            <has-error :form="form" field="ct_tax_YQ"></has-error>
                            
                            <!--===== ct_tax_RG =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_RG" id="ct_tax_RG" type="number" name="ct_tax_RG" placeholder="RG"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_RG') }">
                            <has-error :form="form" field="ct_tax_RG"></has-error>
                            
                            <!--===== ct_tax_PK =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_PK" id="ct_tax_PK" type="number" name="ct_tax_PK" placeholder="PK"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_PK') }">
                            <has-error :form="form" field="ct_tax_PK"></has-error>
                            
                            <!--===== ct_tax_YR =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_YR" id="ct_tax_YR" type="number" name="ct_tax_YR" placeholder="YR"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_YR') }">
                            <has-error :form="form" field="ct_tax_YR"></has-error>
                            
                            <!--===== ct_tax_SF =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_SF" id="ct_tax_SF" type="number" name="ct_tax_SF" placeholder="SF"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_SF') }">
                            <has-error :form="form" field="ct_tax_SF"></has-error>
                            
                            <!--===== ct_tax_PTT =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_PTT" id="ct_tax_PTT" type="number" name="ct_tax_PTT" placeholder="PTT"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_PTT') }">
                            <has-error :form="form" field="ct_tax_PTT"></has-error>
                            
                            <!--===== ct_tax_OAS =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_OAS" id="ct_tax_OAS" type="number" name="ct_tax_OAS" placeholder="OAS"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_OAS') }">
                            <has-error :form="form" field="ct_tax_OAS"></has-error>
                            
                            <!--===== ct_tax_PSF =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_PSF" id="ct_tax_PSF" type="number" name="ct_tax_PSF" placeholder="PSF"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_PSF') }">
                            <has-error :form="form" field="ct_tax_PSF"></has-error>
                            
                            <!--===== ct_tax_PB =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_PB" id="ct_tax_PB" type="number" name="ct_tax_PB" placeholder="PB"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_PB') }">
                            <has-error :form="form" field="ct_tax_PB"></has-error>
                            
                            <!--===== ct_tax_OAD =====-->
                            <input v-model.number="ctInvoiceItem.ct_tax_OAD" id="ct_tax_OAD" type="number" name="ct_tax_OAD" placeholder="OAD"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_tax_OAD') }">
                            <has-error :form="form" field="ct_tax_OAD"></has-error>
                        
                        </div>
                      </div>
                    </td>
                    
                    <td>
                      <!--Total Taxes Break Up-->
                      <input :value="getCtTotalTaxes(key)" id="ct_total_tax_breakup" readonly type="number" size="10" name="ct_total_tax_breakup"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_total_tax_breakup') }">
                      <has-error :form="form" field="ct_total_tax_breakup"></has-error>
                    </td>
                      <!--Fares-->
                    <td>
                      <input v-model.number="ctInvoiceItem.ct_fares" type="number" size="10" name="ct_fares"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_fares') }">
                      <has-error :form="form" field="ct_fares"></has-error>
                    </td>
                    <!--Sub Total -->
                    <td>
                      <input :value="getCtSubTotal(key)" id="ct_sub_total" type="number" readonly size="10" name="ct_sub_total"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ct_sub_total') }">
                      <has-error :form="form" field="ct_sub_total"></has-error>
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
                    <td id="borderless" class="table-empty" colspan="5"><strong></strong></td> 
                    <td id="fillcolor" class="table-label" colspan="3"><strong>Fares</strong></td>
                    <td class="table-amount">
                      <input :value="getCtFareTotal()" type="text" readonly name="ct_invoice_fares_total" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_fares_total') }">
                    </td> 
                    </tr>

                    <tr>
                    <td id="borderless" class="table-empty" colspan="5"><strong></strong></td> 
                    <td id="fillcolor" class="table-label" colspan="3"><strong>Taxes</strong></td>
                    <td class="table-amount">
                      <!-- ==========TAXES INDIVIDUAL TOTAL========== -->
                      <input :value="getCtTaxSBTotal()" type="hidden" readonly name="ct_invoice_total_tax_SB" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_SB') }">

                      <input :value="getCtTaxSRPTotal()" type="hidden" readonly name="ct_invoice_total_tax_SRP" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_SRP') }">
                      
                      <input :value="getCtTaxYQTotal()" type="hidden" readonly name="ct_invoice_total_tax_YQ" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_YQ') }">
                      
                      <input :value="getCtTaxRGTotal()" type="hidden" readonly name="ct_invoice_total_tax_RG" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_RG') }">
                      
                      <input :value="getCtTaxPKTotal()" type="hidden" readonly name="ct_invoice_total_tax_PK" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_PK') }">
                      
                      <input :value="getCtTaxYRTotal()" type="hidden" readonly name="ct_invoice_total_tax_YR" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_YR') }">
                      
                      <input :value="getCtTaxSFTotal()" type="hidden" readonly name="ct_invoice_total_tax_SF" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_SF') }">
                      
                      <input :value="getCtTaxPTTTotal()" type="hidden" readonly name="ct_invoice_total_tax_PTT" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_PTT') }">
                      
                      <input :value="getCtTaxOASTotal()" type="hidden" readonly name="ct_invoice_total_tax_OAS" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_OAS') }">
                      
                      <input :value="getCtTaxPSFTotal()" type="hidden" readonly name="ct_invoice_total_tax_PSF" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_PSF') }">
                      
                      <input :value="getCtTaxPBTotal()" type="hidden" readonly name="ct_invoice_total_tax_PB" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_PB') }">
                      
                      <input :value="getCtTaxOADTotal()" type="hidden" readonly name="ct_invoice_total_tax_OAD" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_total_tax_OAD') }">
                      
                      <!-- ==========TAXES INDIVIDUAL TOTAL========== -->
                      <input :value="getCtTaxGTotal()" type="text" readonly name="ct_invoice_taxes_grand_total" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_taxes_grand_total') }">
                    </td> 
                    </tr>

                    <tr>
                    <td id="borderless" class="table-empty" colspan="5"><strong></strong></td> 
                    <td id="fillcolor" class="table-label" colspan="3"><strong>Total</strong></td>
                    <td class="table-amount">
                      <input :value="getCtGrandTotal()" type="text" readonly name="ct_invoice_grand_total" class="form-control" :class="{ 'is-invalid': form.errors.has('ct_invoice_grand_total') }">
                    </td> 
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
      selectedTicketInvoiceId: false,
      viewInvoice: false,
      ticketInvoices: {},
      ctInvoices: {},
      customers: null,
      form: new Form({
        id: "",
        customer_id: "",
        ct_invoice_no: "",
        ct_invoice_date: "",
        ct_invoice_fares_total: 0,
        ct_invoice_taxes_grand_total: 0,
        ct_invoice_grand_total: 0,
        ct_invoice_grand_total_words: "",
        ct_invoice_terms: "",

        ct_invoice_total_tax_SB: 0,
        ct_invoice_total_tax_SRP: 0,
        ct_invoice_total_tax_YQ: 0,
        ct_invoice_total_tax_RG: 0,
        ct_invoice_total_tax_PK: 0,
        ct_invoice_total_tax_YR: 0,
        ct_invoice_total_tax_SF: 0,
        ct_invoice_total_tax_PTT: 0,
        ct_invoice_total_tax_OAS: 0,
        ct_invoice_total_tax_PSF: 0,
        ct_invoice_total_tax_PB: 0,
        ct_invoice_total_tax_OAD: 0,

        ctInvoiceItems: [
          {
            id: "",
            ct_invoice_id: "",
            ct_passenger_name: "",
            ct_ticket_no: "",
            ct_flight_no: "",
            ct_departure_date: "",
            ct_sector: "",
            ct_tax_SB: "",
            ct_tax_SRP: "",
            ct_tax_YQ: "",
            ct_tax_RG: "",
            ct_tax_PK: "",
            ct_tax_YR: "",
            ct_tax_SF: "",
            ct_tax_PTT: "",
            ct_tax_OAS: "",
            ct_tax_PSF: "",
            ct_tax_PB: "",
            ct_tax_OAD: "",
            ct_fares: 0,
            ct_total_tax_breakup: 0,
            ct_sub_total: 0
          }
        ]
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get('api/ct-invoice?page=' + page).then(response => {
        this.ctInvoices = response.data;
      });
    },
    /*=====TOTAL CT TAX SB=====*/
    getCtTaxSBTotal() {
      let calCtTaxSBTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxSBTotal += parseFloat(i.ct_tax_SB))
      );

      this.form.ct_invoice_total_tax_SB = calCtTaxSBTotal;

      return calCtTaxSBTotal;
    },
    /*=====TOTAL CT TAX SRP=====*/
    getCtTaxSRPTotal() {
      let calCtTaxSRPTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxSRPTotal += parseFloat(i.ct_tax_SRP))
      );

      this.form.ct_invoice_total_tax_SRP = calCtTaxSRPTotal;

      return calCtTaxSRPTotal;
    },
    /*=====TOTAL CT TAX YQ=====*/
    getCtTaxYQTotal() {
      let calCtTaxYQTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxYQTotal += parseFloat(i.ct_tax_YQ))
      );

      this.form.ct_invoice_total_tax_YQ = calCtTaxYQTotal;

      return calCtTaxYQTotal;
    },
    /*=====TOTAL CT TAX RG=====*/
    getCtTaxRGTotal() {
      let calCtTaxRGTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxRGTotal += parseFloat(i.ct_tax_RG))
      );

      this.form.ct_invoice_total_tax_RG = calCtTaxRGTotal;

      return calCtTaxRGTotal;
    },
    /*=====TOTAL CT TAX PK=====*/
    getCtTaxPKTotal() {
      let calCtTaxPKTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxPKTotal += parseFloat(i.ct_tax_PK))
      );

      this.form.ct_invoice_total_tax_PK = calCtTaxPKTotal;

      return calCtTaxPKTotal;
    },
    /*=====TOTAL CT TAX YR=====*/
    getCtTaxYRTotal() {
      let calCtTaxYRTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxYRTotal += parseFloat(i.ct_tax_YR))
      );

      this.form.ct_invoice_total_tax_YR = calCtTaxYRTotal;

      return calCtTaxYRTotal;
    },
    /*=====TOTAL CT TAX SF=====*/
    getCtTaxSFTotal() {
      let calCtTaxSFTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxSFTotal += parseFloat(i.ct_tax_SF))
      );

      this.form.ct_invoice_total_tax_SF = calCtTaxSFTotal;

      return calCtTaxSFTotal;
    },
    /*=====TOTAL CT TAX PTT=====*/
    getCtTaxPTTTotal() {
      let calCtTaxPTTTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxPTTTotal += parseFloat(i.ct_tax_PTT))
      );

      this.form.ct_invoice_total_tax_PTT = calCtTaxPTTTotal;

      return calCtTaxPTTTotal;
    },
    /*=====TOTAL CT TAX OAS=====*/
    getCtTaxOASTotal() {
      let calCtTaxOASTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxOASTotal += parseFloat(i.ct_tax_OAS))
      );

      this.form.ct_invoice_total_tax_OAS = calCtTaxOASTotal;

      return calCtTaxOASTotal;
    },
    /*=====TOTAL CT TAX PSF=====*/
    getCtTaxPSFTotal() {
      let calCtTaxPSFTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxPSFTotal += parseFloat(i.ct_tax_PSF))
      );

      this.form.ct_invoice_total_tax_PSF = calCtTaxPSFTotal;

      return calCtTaxPSFTotal;
    },
    /*=====TOTAL CT TAX PB=====*/
    getCtTaxPBTotal() {
      let calCtTaxPBTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxPBTotal += parseFloat(i.ct_tax_PB))
      );

      this.form.ct_invoice_total_tax_PB = calCtTaxPBTotal;

      return calCtTaxPBTotal;
    },
    /*=====TOTAL CT TAX OAD=====*/
    getCtTaxOADTotal() {
      let calCtTaxOADTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calCtTaxOADTotal += parseFloat(i.ct_tax_OAD))
      );

      this.form.ct_invoice_total_tax_OAD = calCtTaxOADTotal;

      return calCtTaxOADTotal;
    },
    loadTicketInvoices() {
      axios
        .get("api/ticket-invoice")
        .then(({ data }) => (this.ticketInvoices = data.data));
    },
    getRecord: function(e) {
      axios
        .get("api/ticket-invoice/fetch/" + this.selectedTicketInvoiceId)
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
    getCtTotalTaxes(key) {
      let calTaxTotal =
        parseInt(this.form.ctInvoiceItems[key].ct_tax_SB) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_SRP) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_YQ) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_RG) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_PK) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_YR) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_SF) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_PTT) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_OAS) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_PSF) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_PB) +
        parseInt(this.form.ctInvoiceItems[key].ct_tax_OAD);

      this.form.ctInvoiceItems[key].ct_total_tax_breakup = calTaxTotal;

      return calTaxTotal;
    },
    getCtSubTotal(key) {
      let calSubTotal =
        parseInt(this.form.ctInvoiceItems[key].ct_total_tax_breakup) +
        parseInt(this.form.ctInvoiceItems[key].ct_fares);

      this.form.ctInvoiceItems[key].ct_sub_total = calSubTotal;

      return calSubTotal;
    },
    getCtFareTotal() {
      let calFareTotal = 0;

      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calFareTotal += parseFloat(i.ct_fares))
      );

      this.form.ct_invoice_fares_total = calFareTotal;

      return calFareTotal;
    },
    getCtTaxGTotal() {
      let calTaxGTotal = 0;
      Object.values(this.form.ctInvoiceItems).forEach(
        i => (calTaxGTotal += i.ct_total_tax_breakup)
      );

      this.form.ct_invoice_taxes_grand_total = calTaxGTotal;

      return calTaxGTotal;
    },
    getCtGrandTotal() {
      let calGrandTotal = 0;

      calGrandTotal =
        parseInt(this.form.ct_invoice_fares_total) +
        parseInt(this.form.ct_invoice_taxes_grand_total);

      this.form.ct_invoice_grand_total = calGrandTotal;

      return calGrandTotal;
    },

    /*=================LOAD TICKET INVOICE CODE=================*/
    loadCtInvoices() {
      axios.get("api/ct-invoice").then(({ data }) => (this.ctInvoices = data));
    },
    /*=================END LOAD TICKET INVOICE CODE=================*/

    /*=================LOAD VENDORS CODE=================*/
    loadCustomers() {
      axios
        .get("api/customer")
        .then(({ data }) => (this.customers = data.data));
    },
    /*=================END LOAD VENDORS CODE=================*/

    /*=================CREATE TICKET INVOICE CODE=================*/
    createCtInvoice() {
      this.$Progress.start();
      this.form
        .post("api/ct-invoice")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewCtInvoice").modal("hide");
          toast({
            type: "success",
            title: "Customer Invoice Created Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    },
    /*=================END CREATE TICKET INVOICE CODE=================*/

    /*==============EDIT INVOICE CODE==============*/
    editModel(ctInvoice) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewCtInvoice").modal("show");
      // console.log("edit  => ",ctInvoice)
      this.form.fill(ctInvoice);

      this.form.ctInvoiceItems = ctInvoice.ct_invoice_items;
      // console.log("after fill  => ",this.form)
    },
    /*==============END EDIT INVOICE CODE==============*/

    /*=================ADD ITEMS FIELDS CODE=================*/
    addItems(key) {
      this.form.ctInvoiceItems.splice(key + 1, 0, {
        id: "",
        ct_invoice_id: "",
        ct_passenger_name: "",
        ct_ticket_no: "",
        ct_flight_no: "",
        ct_departure_date: "",
        ct_sector: "",
        ct_tax_SB: "",
        ct_tax_SRP: "",
        ct_tax_YQ: "",
        ct_tax_RG: "",
        ct_tax_PK: "",
        ct_tax_YR: "",
        ct_tax_SF: "",
        ct_tax_PTT: "",
        ct_tax_OAS: "",
        ct_tax_PSF: "",
        ct_tax_PB: "",
        ct_tax_OAD: "",
        ct_total_tax_breakup: 0,
        ct_fares: 0,
        ct_sub_total: 0
      });
    },
    /*=================END ADD ITEMS FIELDS CODE=================*/

    /*=================REMOVE ITEMS FIELDS CODE=================*/
    removeItems(key) {
      this.form.ctInvoiceItems.splice(key, 1);
    },
    /*=================END REMOVE ITEMS FIELDS CODE=================*/

    /*=================FORMAT MONEY CODE=================*/
    formatPrice(value) {
      let val = (value / 1).toFixed().replace(".", ",");

      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //75.674,00
    },
    /*=================END FORMAT MONEY CODE=================*/

    /*=================OPEN NEW MODEL CODE=================*/
    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewCtInvoice").modal("show");
    }
  },
  /*=================OPEN NEW MODEL CODE=================*/

  mounted() {
    this.loadTicketInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadTicketInvoices();
    });
    this.loadCtInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadCtInvoices();
    });
    this.loadCustomers();
    Fire.$on("RefreshTable", () => {
      this.loadCustomers();
    });
  }
};
</script>

