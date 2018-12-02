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
   overflow-y:auto !important;
   height: 50px !important;
   overflow:scroll !important;
}
</style>
<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Vendor Invoice Table</h3>

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
                  
                  <tr v-for="ticketInvoice in ticketInvoices.data" :key="ticketInvoice.id">
                    <td>{{ ticketInvoice.ticket_invoice_no }}</td>
                    <td>{{ ticketInvoice.vendor.vendor_company_name }}</td>
                    <td>{{ ticketInvoice.ticket_invoice_date | myDate}}</td>
                    <td>{{ formatPrice(ticketInvoice.ticket_invoice_grand_total) }}</td>
                    <td>
                        <a href="#" @click="editModel(ticketInvoice)">
                            <i class="fas fa-edit fa-lg text-orange"></i>
                        </a>
                        &nbsp;
                        <a href="#">
                            <i class="fas fa-times fa-lg text-red"></i>
                        </a>
                        &nbsp;
                        <router-link v-bind:to="{name: 'ticketInvoiceView', params: {id: ticketInvoice.id}}">
                            <i class="fas fa-eye fa-lg text-blue"></i>
                        </router-link>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="ticketInvoices" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            </div>
        </div>

    <div class="modal fade" id="addNewTicketInvoice" tabindex="-1" role="dialog" aria-labelledby="addNewTicketInvoiceTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-full float-sm-right" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewTicketInvoiceTitle">Add Vendor Invoice</h5>
                <h5 class="modal-title" v-show="editmode" id="updateTicketInvoiceTitle">Update Vendor's Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form @submit.prevent="editmode ? updateTicketInvoice() : createTicketInvoice()">
            <div class="modal-body">
              <div class="row">

                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="ticket_invoice_no" class="control-label">Invoice No.</label>
                    <input v-model="form.ticket_invoice_no" type="text" name="ticket_invoice_no" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_no') }">
                    <has-error :form="form" field="ticket_invoice_no"></has-error>
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
                  <label for="ticket_invoice_date">Invoice Date</label>
                  <input v-model="form.ticket_invoice_date" type="date" name="ticket_invoice_date" placeholder=""
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_date') }">
                  <has-error :form="form" field="ticket_invoice_date"></has-error>
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
                  <tr v-for="(ticketInvoiceItem, key) in form.ticketInvoiceItems" :key="key">
                      <!--Passenger Name-->
                    <td>
                      <input v-model.number="ticketInvoiceItem.passenger_name" size="40" type="text" name="passenger_name" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('passenger_name') }">
                      <has-error :form="form" field="passenger_name"></has-error>
                    </td>
                    <!--Ticket No.-->
                    <td>
                      <input v-model="ticketInvoiceItem.ticket_no" size="24" type="text" name="ticket_no" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('ticket_no') }">
                      <has-error :form="form" field="ticket_no"></has-error>
                    </td>
                    <!--Flight No.-->
                    <td>
                      <input v-model="ticketInvoiceItem.flight_no" size="7" type="text" name="flight_no" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('flight_no') }">
                      <has-error :form="form" field="flight_no"></has-error>
                    </td>
                    <!--Departure Date-->
                    <td>
                      <input v-model="ticketInvoiceItem.departure_date" type="date" name="departure_date" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('departure_date') }">
                      <has-error :form="form" field="departure_date"></has-error>
                    </td>
                    <!--Sector-->
                    <td>
                      <input v-model="ticketInvoiceItem.sector" type="text" name="sector" class="table-control form-control" :class="{ 'is-invalid': form.errors.has('sector') }">
                      <has-error :form="form" field="sector"></has-error>
                    </td>
                    <!--DROPDOWN MENU-->
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu form-group" aria-labelledby="dropdownMenuButton">
                        
                          <!--Taxes BreakUp-->
                            <input v-model.number="ticketInvoiceItem.tax_SB" id="tax_SB" type="number" name="tax_SB" placeholder="SB"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_SB') }">
                            <has-error :form="form" field="tax_SB"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_SRP" id="tax_SRP" type="number" name="tax_SRP" placeholder="SRP"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_SRP') }">
                            <has-error :form="form" field="tax_SRP"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_YQ" id="tax_YQ" type="number" name="tax_YQ" placeholder="YQ"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_YQ') }">
                            <has-error :form="form" field="tax_YQ"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_RG" id="tax_RG" type="number" name="tax_RG" placeholder="RG"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_RG') }">
                            <has-error :form="form" field="tax_RG"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_PK" id="tax_PK" type="number" name="tax_PK" placeholder="PK"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_PK') }">
                            <has-error :form="form" field="tax_PK"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_YR" id="tax_YR" type="number" name="tax_YR" placeholder="YR"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_YR') }">
                            <has-error :form="form" field="tax_YR"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_SF" id="tax_SF" type="number" name="tax_SF" placeholder="SF"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_SF') }">
                            <has-error :form="form" field="tax_SF"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_PTT" id="tax_PTT" type="number" name="tax_PTT" placeholder="PTT"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_PTT') }">
                            <has-error :form="form" field="tax_PTT"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_OAS" id="tax_OAS" type="number" name="tax_OAS" placeholder="OAS"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_OAS') }">
                            <has-error :form="form" field="tax_OAS"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_PSF" id="tax_PSF" type="number" name="tax_PSF" placeholder="PSF"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_PSF') }">
                            <has-error :form="form" field="tax_PSF"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_PB" id="tax_PB" type="number" name="tax_PB" placeholder="PB"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_PB') }">
                            <has-error :form="form" field="tax_PB"></has-error>
                            
                            <input v-model.number="ticketInvoiceItem.tax_OAD" id="tax_OAD" type="number" name="tax_OAD" placeholder="OAD"
                            class="table-control form-control" :class="{ 'is-invalid': form.errors.has('tax_OAD') }">
                            <has-error :form="form" field="tax_OAD"></has-error>
                        
                        </div>
                      </div>
                    </td>
                    
                    <td>
                      <!--Total Taxes Break Up-->
                      <input :value="getTotalTaxes(key)" id="total_tax_breakup" readonly type="number" size="10" name="total_tax_breakup"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('total_tax_breakup') }">
                      <has-error :form="form" field="total_tax_breakup"></has-error>
                    </td>
                      <!--Fares-->
                    <td>
                      <input v-model.number="ticketInvoiceItem.fares" type="number" size="10" name="fares"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('fares') }">
                      <has-error :form="form" field="fares"></has-error>
                    </td>
                    <!--Sub Total -->
                    <td>
                      <input :value="getSubTotal(key)" type="number" readonly size="10" name="sub_total"
                      class="table-control form-control" :class="{ 'is-invalid': form.errors.has('sub_total') }">
                      <has-error :form="form" field="sub_total"></has-error>
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
                    <td  id="borderless" class="table-empty" colspan="5"><strong></strong></td> 
                    <td  id="fillcolor" class="table-label" colspan="3"><strong>Fares</strong></td>
                    <td  class="table-amount">
                      <input :value="getFareTotal()" type="text" readonly name="ticket_invoice_fares_total" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_fares_total') }"></td> 
                    </tr>

                    <tr>
                    <td  id="borderless" class="table-empty" colspan="5"><strong></strong></td> 
                    <td  id="fillcolor" class="table-label" colspan="3"><strong>Taxes</strong></td>
                    <td  class="table-amount">
                      
                      <!-- ==========TAXES INDIVIDUAL TOTAL========== -->
                      <input :value="getTaxSBTotal()" type="hidden" readonly name="ticket_invoice_total_tax_SB" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_SB') }">

                      <input :value="getTaxSRPTotal()" type="hidden" readonly name="ticket_invoice_total_tax_SRP" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_SRP') }">
                      
                      <input :value="getTaxYQTotal()" type="hidden" readonly name="ticket_invoice_total_tax_YQ" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_YQ') }">
                      
                      <input :value="getTaxRGTotal()" type="hidden" readonly name="ticket_invoice_total_tax_RG" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_RG') }">
                      
                      <input :value="getTaxPKTotal()" type="hidden" readonly name="ticket_invoice_total_tax_PK" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_PK') }">
                      
                      <input :value="getTaxYRTotal()" type="hidden" readonly name="ticket_invoice_total_tax_YR" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_YR') }">
                      
                      <input :value="getTaxSFTotal()" type="hidden" readonly name="ticket_invoice_total_tax_SF" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_SF') }">
                      
                      <input :value="getTaxPTTTotal()" type="hidden" readonly name="ticket_invoice_total_tax_PTT" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_PTT') }">
                      
                      <input :value="getTaxOASTotal()" type="hidden" readonly name="ticket_invoice_total_tax_OAS" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_OAS') }">
                      
                      <input :value="getTaxPSFTotal()" type="hidden" readonly name="ticket_invoice_total_tax_PSF" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_PSF') }">
                      
                      <input :value="getTaxPBTotal()" type="hidden" readonly name="ticket_invoice_total_tax_PB" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_PB') }">
                      
                      <input :value="getTaxOADTotal()" type="hidden" readonly name="ticket_invoice_total_tax_OAD" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_total_tax_OAD') }">
                      
                      <!-- ==========TAX GRAND TOTAL========== -->
                      <input :value="getTaxGTotal()" type="text" readonly name="ticket_invoice_taxes_grand_total" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_taxes_grand_total') }"></td> 
                    </tr>

                    <tr>
                    <td  id="borderless" class="table-empty" colspan="5"><strong></strong></td> 
                    <td  id="fillcolor" class="table-label" colspan="3"><strong>Total</strong></td>
                    <td  class="table-amount">
                      <input :value="getGrandTotal()" type="text" readonly name="ticket_invoice_grand_total" class="form-control" :class="{ 'is-invalid': form.errors.has('ticket_invoice_grand_total') }"></td> 
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
      ticketInvoices: {},
      vendors: null,
      selectVendors: null,
      form: new Form({
        id: "",
        vendor_id: "",
        ticket_invoice_no: "",
        ticket_invoice_date: "",
        ticket_invoice_fares_total: 0,
        ticket_invoice_taxes_grand_total: 0,
        ticket_invoice_grand_total: 0,
        ticket_invoice_grand_total_words: "",
        ticket_invoice_terms: "",
        ticket_invoice_total_tax_SB: 0,
        ticket_invoice_total_tax_SRP: 0,
        ticket_invoice_total_tax_YQ: 0,
        ticket_invoice_total_tax_RG: 0,
        ticket_invoice_total_tax_PK: 0,
        ticket_invoice_total_tax_YR: 0,
        ticket_invoice_total_tax_SF: 0,
        ticket_invoice_total_tax_PTT: 0,
        ticket_invoice_total_tax_OAS: 0,
        ticket_invoice_total_tax_PSF: 0,
        ticket_invoice_total_tax_PB: 0,
        ticket_invoice_total_tax_OAD: 0,

        ticketInvoiceItems: [
          {
            id: "",
            ticket_invoice_id: "",
            passenger_name: "",
            ticket_no: "",
            flight_no: "",
            departure_date: "",
            sector: "",
            tax_SB: "",
            tax_SRP: "",
            tax_YQ: "",
            tax_RG: "",
            tax_PK: "",
            tax_YR: "",
            tax_SF: "",
            tax_PTT: "",
            tax_OAS: "",
            tax_PSF: "",
            tax_PB: "",
            tax_OAD: "",
            fares: 0,
            total_tax_breakup: 0,
            sub_total: 0
          }
        ]
      })
    };
  },
  methods: {
        updateDate: function(date) {
      this.date = date;
    },
    getResults(page = 1) {
      axios.get("api/ticket-invoice?page=" + page).then(response => {
        this.ticketInvoices = response.data;
      });
    },
    /*=====TOTAL SB=====*/
    getTaxSBTotal() {
      let calTaxSBTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxSBTotal += parseFloat(i.tax_SB))
      );

      this.form.ticket_invoice_total_tax_SB = calTaxSBTotal;

      return calTaxSBTotal;
    },
    /*=====TOTAL SRP=====*/
    getTaxSRPTotal() {
      let calTaxSRPTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxSRPTotal += parseFloat(i.tax_SRP))
      );

      this.form.ticket_invoice_total_tax_SRP = calTaxSRPTotal;

      return calTaxSRPTotal;
    },
    /*=====TOTAL YQ=====*/
    getTaxYQTotal() {
      let calTaxYQTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxYQTotal += parseFloat(i.tax_YQ))
      );

      this.form.ticket_invoice_total_tax_YQ = calTaxYQTotal;

      return calTaxYQTotal;
    },
    /*=====TOTAL RG=====*/
    getTaxRGTotal() {
      let calTaxRGTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxRGTotal += parseFloat(i.tax_RG))
      );

      this.form.ticket_invoice_total_tax_RG = calTaxRGTotal;

      return calTaxRGTotal;
    },
    /*=====TOTAL PK=====*/
    getTaxPKTotal() {
      let calTaxPKTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxPKTotal += parseFloat(i.tax_PK))
      );

      this.form.ticket_invoice_total_tax_PK = calTaxPKTotal;

      return calTaxPKTotal;
    },
    /*=====TOTAL YR=====*/
    getTaxYRTotal() {
      let calTaxYRTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxYRTotal += parseFloat(i.tax_YR))
      );

      this.form.ticket_invoice_total_tax_YR = calTaxYRTotal;

      return calTaxYRTotal;
    },
    /*=====TOTAL SF=====*/
    getTaxSFTotal() {
      let calTaxSFTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxSFTotal += parseFloat(i.tax_SF))
      );

      this.form.ticket_invoice_total_tax_SF = calTaxSFTotal;

      return calTaxSFTotal;
    },
    /*=====TOTAL PTT=====*/
    getTaxPTTTotal() {
      let calTaxPTTTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxPTTTotal += parseFloat(i.tax_PTT))
      );

      this.form.ticket_invoice_total_tax_PTT = calTaxPTTTotal;

      return calTaxPTTTotal;
    },
    /*=====TOTAL OAS=====*/
    getTaxOASTotal() {
      let calTaxOASTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxOASTotal += parseFloat(i.tax_OAS))
      );

      this.form.ticket_invoice_total_tax_OAS = calTaxOASTotal;

      return calTaxOASTotal;
    },
    /*=====TOTAL PSF=====*/
    getTaxPSFTotal() {
      let calTaxPSFTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxPSFTotal += parseFloat(i.tax_PSF))
      );

      this.form.ticket_invoice_total_tax_PSF = calTaxPSFTotal;

      return calTaxPSFTotal;
    },
    /*=====TOTAL PB=====*/
    getTaxPBTotal() {
      let calTaxPBTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxPBTotal += parseFloat(i.tax_PB))
      );

      this.form.ticket_invoice_total_tax_PB = calTaxPBTotal;

      return calTaxPBTotal;
    },
    /*=====TOTAL OAD=====*/
    getTaxOADTotal() {
      let calTaxOADTotal = 0;

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calTaxOADTotal += parseFloat(i.tax_OAD))
      );

      this.form.ticket_invoice_total_tax_OAD = calTaxOADTotal;

      return calTaxOADTotal;
    },
    getTotalTaxes(key) {
      let calTaxTotal =
        parseInt(this.form.ticketInvoiceItems[key].tax_SB) +
        parseInt(this.form.ticketInvoiceItems[key].tax_SRP) +
        parseInt(this.form.ticketInvoiceItems[key].tax_YQ) +
        parseInt(this.form.ticketInvoiceItems[key].tax_RG) +
        parseInt(this.form.ticketInvoiceItems[key].tax_PK) +
        parseInt(this.form.ticketInvoiceItems[key].tax_YR) +
        parseInt(this.form.ticketInvoiceItems[key].tax_SF) +
        parseInt(this.form.ticketInvoiceItems[key].tax_PTT) +
        parseInt(this.form.ticketInvoiceItems[key].tax_OAS) +
        parseInt(this.form.ticketInvoiceItems[key].tax_PSF) +
        parseInt(this.form.ticketInvoiceItems[key].tax_PB) +
        parseInt(this.form.ticketInvoiceItems[key].tax_OAD);

      this.form.ticketInvoiceItems[key].total_tax_breakup = calTaxTotal;

      return calTaxTotal;
    },
    getSubTotal(key) {
      let calSubTotal =
        parseInt(this.form.ticketInvoiceItems[key].total_tax_breakup) +
        parseInt(this.form.ticketInvoiceItems[key].fares);

      this.form.ticketInvoiceItems[key].sub_total = calSubTotal;

      return calSubTotal;
    },
    getFareTotal() {
      let calFareTotal = 0;

      // console.log("1 => ", this.form);
      // console.log("2 =>", this.form.ticketInvoiceItems);//this puts  undefined why ?

      Object.values(this.form.ticketInvoiceItems).forEach(
        i => (calFareTotal += parseFloat(i.fares))
      );

      this.form.ticket_invoice_fares_total = calFareTotal;

      return calFareTotal;
    },
    getTaxGTotal() {
      let calTaxGTotal = 0;

      this.form.ticketInvoiceItems.forEach(
        i => (calTaxGTotal += i.total_tax_breakup)
      );

      this.form.ticket_invoice_taxes_grand_total = calTaxGTotal;

      return calTaxGTotal;
    },
    getGrandTotal() {
      let calGrandTotal = 0;

      calGrandTotal =
        parseInt(this.form.ticket_invoice_fares_total) +
        parseInt(this.form.ticket_invoice_taxes_grand_total);

      this.form.ticket_invoice_grand_total = calGrandTotal;

      return calGrandTotal;
    },

    /*=================LOAD TICKET INVOICE CODE=================*/
    loadTicketInvoices() {
      axios
        .get("api/ticket-invoice")
        .then(({ data }) => (this.ticketInvoices = data));
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

    /*=================END LOAD VENDORS CODE=================*/

    /*=================CREATE TICKET INVOICE CODE=================*/
    createTicketInvoice() {
      this.$Progress.start();
      this.form
        .post("api/ticket-invoice")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewTicketInvoice").modal("hide");
          toast({
            type: "success",
            title: "Invoice Created Successfully"
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
    editModel(ticketInvoice) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewTicketInvoice").modal("show");
      //  console.log("edit  => ",ticketInvoice)
      this.form.fill(ticketInvoice);

      this.form.ticketInvoiceItems = ticketInvoice.ticket_invoice_items;

      // console.log("after fill  => ",this.form)
    },
    /*==============END EDIT INVOICE CODE==============*/

    /*=================ADD ITEMS FIELDS CODE=================*/
    addItems(key) {
      this.form.ticketInvoiceItems.splice(key + 1, 0, {
        id: "",
        ticket_invoice_id: "",
        passenger_name: "",
        ticket_no: "",
        flight_no: "",
        departure_date: "",
        sector: "",
        tax_SB: "",
        tax_SRP: "",
        tax_YQ: "",
        tax_RG: "",
        tax_PK: "",
        tax_YR: "",
        tax_SF: "",
        tax_PTT: "",
        tax_OAS: "",
        tax_PSF: "",
        tax_PB: "",
        tax_OAD: "",
        total_tax_breakup: 0,
        fares: 0,
        sub_total: 0
      });
    },
    /*=================END ADD ITEMS FIELDS CODE=================*/

    /*=================REMOVE ITEMS FIELDS CODE=================*/
    removeItems(key) {
      this.form.ticketInvoiceItems.splice(key, 1);
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
      $("#addNewTicketInvoice").modal("show");
    }
  },
  /*=================OPEN NEW MODEL CODE=================*/
  mounted() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findVTI?q=" + query)
        .then(data => {
          this.ticketInvoices = data.data;
        })
        .catch();
    });
    this.loadTicketInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadTicketInvoices();
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
