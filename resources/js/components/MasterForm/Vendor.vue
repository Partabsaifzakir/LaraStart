<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Vendor's Table</h3>

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
                    <th>Company Name</th>
                    <th>Contact Number</th>
                    <th>Contact Person Name</th>
                    <th>Vendor Address</th>
                    <th>Vendor Email</th>
                    <th>Modify</th>
                  </tr>
                  
                  <tr v-for="vendor in vendors" :key="vendor.id">
                    <td>{{ vendor.vendor_company_name | upText }}</td>
                    <td>{{ vendor.vendor_contact }}</td>
                    <td>{{ vendor.vendor_person_name | upText}}</td>
                    <td>{{ vendor.vendor_address | upText}}</td>
                    <td>{{ vendor.vendor_email }}</td>
                    <td>
                        <a href="#" @click="editModel(vendor)">
                            <i class="fas fa-user-edit fa-lg text-orange"></i>
                        </a>
                        &nbsp;
                        <a href="#" @click="deleteVendor(vendor.id)">
                            <i class="fas fa-user-times fa-lg text-red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
        </div>

    <div class="modal fade" id="addNewVendor" tabindex="-1" role="dialog" aria-labelledby="addNewVendorTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewVendorTitle">Add New Vendor</h5>
                <h5 class="modal-title" v-show="editmode" id="updateVendorTitle">Update User's Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form @submit.prevent="editmode ? updateVendor() : createVendor()">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.vendor_company_name" type="text" name="vendor_company_name" placeholder="Enter Company Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('vendor_company_name') }">
                  <has-error :form="form" field="vendor_company_name"></has-error>
                </div>

                <div class="form-group">
                  <input v-model="form.vendor_contact" type="text" name="vendor_contact" placeholder="Enter Contact Number"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('vendor_contact') }">
                  <has-error :form="form" field="vendor_contact"></has-error>
                </div>

                <div class="form-group">
                  <input v-model="form.vendor_person_name" type="text" name="vendor_person_name" placeholder="Enter Person Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('vendor_person_name') }">
                  <has-error :form="form" field="vendor_person_name"></has-error>
                </div>

                <div class="form-group">
                  <input v-model="form.vendor_email" type="email" name="vendor_email" placeholder="Enter Email Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('vendor_email') }">
                  <has-error :form="form" field="vendor_email"></has-error>
                </div>

                <div class="form-group">
                  <input v-model="form.vendor_address" type="text" name="vendor_address" placeholder="Enter Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('vendor_address') }">
                  <has-error :form="form" field="vendor_address"></has-error>
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
      vendors: {},
      form: new Form({
        id: "",
        vendor_company_name: "",
        vendor_address: "",
        vendor_contact: "",
        vendor_person_name: "",
        vendor_email: ""
      })
    };
  },
  methods: {
    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewVendor").modal("show");
    },
    /*==============LOADING VENDOR==============*/
    loadVendors() {
      axios.get("api/vendor").then(({ data }) => (this.vendors = data.data));
    },
    /*==============END LOADING VENDOR==============*/

    /*==============CREATING VENDOR==============*/
    createVendor() {
      this.$Progress.start();
      this.form
        .post("api/vendor")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewVendor").modal("hide");
          toast({
            type: "success",
            title: "Vendor Created Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    },
    /*==============END CREATING VENDOR==============*/

    /*==============FOR EDITING VENDOR==============*/
    editModel(vendor) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewVendor").modal("show");
      this.form.fill(vendor);
    },
    /*==============END FOR EDITING VENDOR==============*/

    /*==============FOR UPDATING VENDOR==============*/
    updateVendor() {
      this.$Progress.start();
      this.form
        .put("api/vendor/" + this.form.id)
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewVendor").modal("hide");
          toast({
            type: "success",
            title: "Vendor Updated Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
    },
    /*==============END FOR UPDATING VENDOR==============*/

    /*==============FOR DELETING VENDOR==============*/
    deleteVendor(id) {
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
              .delete("api/vendor/" + id)
              .then(() => {
                Fire.$emit("RefreshTable");
                swal("Deleted!", "Your Vendor has been deleted.", "success");
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
    /*==============END FOR DELETING VENDOR==============*/
  },

  mounted() {
    this.loadVendors();
    Fire.$on("RefreshTable", () => {
      this.loadVendors();
    });
  }
};
</script>
