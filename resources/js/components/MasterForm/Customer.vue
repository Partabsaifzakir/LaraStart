<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer's Table</h3>

                <div class="card-tools">
                    <button class="btn btn-default" style="background-color: transparent;" @click="newModel">
                        <i class="fas fa-user-plus fa-2x text-green"></i>
                    </button>       
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>Customer Name</th>
                    <th>Customer Company Name</th>
                    <th>Customer Contact</th>
                    <th>Customer Type</th>
                    <th>Customer Email</th>
                    <th>Modify</th>
                  </tr>
                  
                  <tr v-for="customer in customers.data" :key="customer.id">
                    <td>{{ customer.customer_name | upText}}</td>
                    <td v-if="customer.customer_company_name!==undefined && customer.customer_company_name!=null">{{ customer.customer_company_name | upText}}</td>
                    <td v-else>--------------</td>
                    <td>{{ customer.customer_contact }}</td>
                    <td>{{ customer.customer_type | upText}}</td>
                    <td>{{ customer.customer_email }}</td>
                    
                    <td>
                        <a href="#" @click="editModel(customer)">
                            <i class="fas fa-user-edit fa-lg text-orange"></i>
                        </a>
                        &nbsp;
                        <a href="#" @click="deleteCustomer(customer.id)">
                            <i class="fas fa-user-times fa-lg text-red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="customers" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            </div>
        </div>

    <div class="modal fade" id="addNewCustomer" tabindex="-1" role="dialog" aria-labelledby="addNewCustomerTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewCustomerTitle">Add New Customer</h5>
                <h5 class="modal-title" v-show="editmode" id="updateCustomerTitle">Update User's Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form @submit.prevent="editmode ? updateCustomer() : createCustomer()">
            <div class="modal-body">
                
                <!--CUSTOMER NAME-->
                <div class="form-group">
                  <input v-model="form.customer_name" type="text" name="customer_name" placeholder="Enter Customer Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_name') }">
                  <has-error :form="form" field="customer_name"></has-error>
                </div>

                <!--CUSTOMER COMPANY NAME-->
                <div class="form-group">
                  <input v-model="form.customer_company_name" type="text" name="customer_company_name" placeholder="Enter Customer Company Name (Optional)"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_company_name') }">
                  <has-error :form="form" field="customer_company_name"></has-error>
                </div>

                <!--CUSTOMER CONTACT-->
                <div class="form-group">
                  <input v-model="form.customer_contact" type="text" name="customer_contact" placeholder="Enter Customer Contact"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_contact') }">
                  <has-error :form="form" field="customer_contact"></has-error>
                </div>

                <!--CUSTOMER TYPE-->
                <div class="form-group">
                  <select v-model="form.customer_type" id="customer_type" type="text" name="customer_type"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_type') }">
                    <option value="" disabled selected>Select Customer Type</option>
                    <option value="personal">Personal</option>
                    <option value="corporate">Corporate</option>
                    </select>
                  <has-error :form="form" field="customer_type"></has-error>
                </div>

                <!--CUSTOMER EMAIL-->
                <div class="form-group">
                  <input v-model="form.customer_email" type="email" name="customer_email" placeholder="Enter Customer Email"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_email') }">
                  <has-error :form="form" field="customer_email"></has-error>
                </div>

                <!--CUSTOMER HOME ADDRESS-->
                <div class="form-group">
                  <input v-model="form.customer_home_address" type="text" name="customer_home_address" placeholder="Enter Customer Home Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_home_address') }">
                  <has-error :form="form" field="customer_home_address"></has-error>
                </div>

                <!--CUSTOMER OFFICE ADDRESS-->
                <div class="form-group">
                  <input v-model="form.customer_office_address" type="text" name="customer_office_address" placeholder="Enter Customer Office Address (Optional)"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_office_address') }">
                  <has-error :form="form" field="customer_office_address"></has-error>
                </div>

                <!--CUSTOMER DATE-->
                <div class="form-group">
                  <input v-model="form.customer_date" type="date" name="customer_date" placeholder="Enter Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_date') }">
                  <has-error :form="form" field="customer_date"></has-error>
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
      customers: {},
      form: new Form({
        id: "",
        customer_name: "",
        customer_company_name: "",
        customer_contact: "",
        customer_type: "",
        customer_home_address: "",
        customer_office_address: "",
        customer_email: "",
        customer_date: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/customer?page=" + page).then(response => {
        this.customers = response.data;
      });
    },
    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewCustomer").modal("show");
    },
    /*==============LOADING CUSTOMER==============*/
    loadCustomers() {
      axios.get("api/customer").then(({ data }) => (this.customers = data));
    },
    /*==============END LOADING CUSTOMER==============*/

    /*==============CREATING CUSTOMER==============*/
    createCustomer() {
      this.$Progress.start();
      this.form
        .post("api/customer")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewCustomer").modal("hide");
          toast({
            type: "success",
            title: "Customer Created Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    },
    /*==============END CREATING CUSTOMER==============*/

    /*==============FOR EDITING CUSTOMER==============*/
    editModel(customer) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewCustomer").modal("show");
      this.form.fill(customer);
    },

    /*==============END FOR EDITING CUSTOMER==============*/

    /*==============FOR UPDATING CUSTOMER==============*/
    updateCustomer() {
      this.$Progress.start();
      this.form
        .put("api/customer/" + this.form.id)
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewCustomer").modal("hide");
          toast({
            type: "success",
            title: "Customer Updated Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    },
    /*==============END FOR UPDATING CUSTOMER==============*/

    /*==============FOR DELETING CUSTOMER==============*/
    deleteCustomer(id) {
      swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      })
        .then(result => {
          if (result.value) {
            this.$Progress.start();
            this.form
              .delete("api/customer/" + id)
              .then(() => {
                Fire.$emit("RefreshTable");
                swal("Deleted!", "Your Customer has been deleted.", "success");
                this.$Progress.finish();
              })
              .catch(() => {
                swal("Failed!", "There was something wrong.", "warning");
                this.$Progress.fail();
              });
          }
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    }
    /*==============END FOR DELETING CUSTOMER==============*/
  },

  mounted() {
    Fire.$on('searching', () => {
      
      let query = this.$parent.search;
      axios.get('api/findCustomer?q=' + query)
      .then((data) => {
        this.customers = data.data;
      })
      .catch()
    })
    this.loadCustomers();
    Fire.$on("RefreshTable", () => {
      this.loadCustomers();
    });
  }
};
</script>
