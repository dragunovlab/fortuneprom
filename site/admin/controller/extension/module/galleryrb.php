<?php
class ControllerExtensionModuleGalleryrb extends Controller
{
    private $error = array();
    public function index()
    {
        $this->load->language("extension/module/galleryrb");
        $this->document->setTitle($this->language->get("heading_title"));
        $this->document->addScript("view/javascript/jquery/colorpicker/js/bootstrap-colorpicker.min.js");
        $this->document->addStyle("view/javascript/jquery/colorpicker/css/bootstrap-colorpicker.min.css");
        $this->document->addScript("view/javascript/ckeditor/ckeditor.js");
        $this->document->addScript("view/javascript/ckeditor/ckeditor_init.js");
        $this->load->model("setting/module");
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            if (!isset($this->request->get["module_id"])) {
                $this->model_setting_module->addModule("galleryrb", $this->request->post);
            } else {
                $this->model_setting_module->editModule($this->request->get["module_id"], $this->request->post);
            }
            $this->session->data["success"] = $this->language->get("text_success");
            $this->response->redirect($this->url->link("marketplace/extension", "user_token=" . $this->session->data["user_token"] . "&type=module", true));
        }
		
        $data["ckeditor"] = $this->config->get("config_editor_default");
        if (isset($this->error["warning"])) {
            $data["error_warning"] = $this->error["warning"];
        } else {
            $data["error_warning"] = "";
        }
        if (isset($this->error["name"])) {
            $data["error_name"] = $this->error["name"];
        } else {
            $data["error_name"] = "";
        }
        if (isset($this->error["width_thumb"])) {
            $data["error_width_thumb"] = $this->error["width_thumb"];
        } else {
            $data["error_width_thumb"] = "";
        }
        if (isset($this->error["height_thumb"])) {
            $data["error_height_thumb"] = $this->error["height_thumb"];
        } else {
            $data["error_height_thumb"] = "";
        }
        if (isset($this->error["width_popup"])) {
            $data["error_width_popup"] = $this->error["width_popup"];
        } else {
            $data["error_width_popup"] = "";
        }
        if (isset($this->error["height_popup"])) {
            $data["error_height_popup"] = $this->error["height_popup"];
        } else {
            $data["error_height_popup"] = "";
        }
        $data["breadcrumbs"] = array();
        $data["breadcrumbs"][] = array("text" => $this->language->get("text_home"), "href" => $this->url->link("common/dashboard", "user_token=" . $this->session->data["user_token"], true));
        $data["breadcrumbs"][] = array("text" => $this->language->get("text_extension"), "href" => $this->url->link("marketplace/extension", "user_token=" . $this->session->data["user_token"] . "&type=module", true));
        if (!isset($this->request->get["module_id"])) {
            $data["breadcrumbs"][] = array("text" => $this->language->get("heading_title"), "href" => $this->url->link("extension/module/galleryrb", "user_token=" . $this->session->data["user_token"], true));
        } else {
            $data["breadcrumbs"][] = array("text" => $this->language->get("heading_title"), "href" => $this->url->link("extension/module/galleryrb", "user_token=" . $this->session->data["user_token"] . "&module_id=" . $this->request->get["module_id"], true));
        }
        if (!isset($this->request->get["module_id"])) {
            $data["action"] = $this->url->link("extension/module/galleryrb", "user_token=" . $this->session->data["user_token"], true);
        } else {
            $data["action"] = $this->url->link("extension/module/galleryrb", "user_token=" . $this->session->data["user_token"] . "&module_id=" . $this->request->get["module_id"], true);
        }
        $data["cancel"] = $this->url->link("marketplace/extension", "user_token=" . $this->session->data["user_token"] . "&type=module", true);
        if (isset($this->request->get["module_id"]) && $this->request->server["REQUEST_METHOD"] != "POST") {
            $module_info = $this->model_setting_module->getModule($this->request->get["module_id"]);
        }
        if (isset($this->request->post["name"])) {
            $data["name"] = $this->request->post["name"];
        } else {
            if (!empty($module_info)) {
                $data["name"] = $module_info["name"];
            } else {
                $data["name"] = "";
            }
        }
        if (isset($this->request->post["title"])) {
            $data["title"] = $this->request->post["title"];
        } else {
            if (!empty($module_info)) {
                $data["title"] = $module_info["title"];
            } else {
                $data["title"] = "";
            }
        }
        if (isset($this->request->post["thumb_width"])) {
            $data["thumb_width"] = $this->request->post["thumb_width"];
        } else {
            if (!empty($module_info)) {
                $data["thumb_width"] = $module_info["thumb_width"];
            } else {
                $data["thumb_width"] = "";
            }
        }
        if (isset($this->request->post["thumb_height"])) {
            $data["thumb_height"] = $this->request->post["thumb_height"];
        } else {
            if (!empty($module_info)) {
                $data["thumb_height"] = $module_info["thumb_height"];
            } else {
                $data["thumb_height"] = "";
            }
        }
        if (isset($this->request->post["popup_width"])) {
            $data["popup_width"] = $this->request->post["popup_width"];
        } else {
            if (!empty($module_info)) {
                $data["popup_width"] = $module_info["popup_width"];
            } else {
                $data["popup_width"] = "";
            }
        }
        if (isset($this->request->post["popup_height"])) {
            $data["popup_height"] = $this->request->post["popup_height"];
        } else {
            if (!empty($module_info)) {
                $data["popup_height"] = $module_info["popup_height"];
            } else {
                $data["popup_height"] = "";
            }
        }
        if (isset($this->request->post["resize"])) {
            $data["resize"] = $this->request->post["resize"];
        } else {
            if (!empty($module_info["resize"])) {
                $data["resize"] = $module_info["resize"];
            } else {
                $data["resize"] = "";
            }
        }
        if (isset($this->request->post["colspan"])) {
            $data["colspan"] = $this->request->post["colspan"];
        } else {
            if (!empty($module_info)) {
                $data["colspan"] = $module_info["colspan"];
            } else {
                $data["colspan"] = "4";
            }
        }
        $data["user_token"] = $this->session->data["user_token"];
        $this->load->model("catalog/category");
        if (isset($this->request->post["categories"])) {
            $data["categories"] = $this->request->post["categories"];
        } else {
            if (!empty($module_info["categories"])) {
                $data["categories"] = $module_info["categories"];
            } else {
                $data["categories"] = array();
            }
        }
        $data["gallery_categories"] = array();
        foreach ($data["categories"] as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);
            if ($category_info) {
                $data["gallery_categories"][] = array("category_id" => $category_info["category_id"], "name" => $category_info["path"] ? $category_info["path"] . " &gt; " . $category_info["name"] : $category_info["name"]);
            }
        }
        if (isset($this->request->post["animation"])) {
            $data["animation"] = $this->request->post["animation"];
        } else {
            if (!empty($module_info)) {
                $data["animation"] = $module_info["animation"];
            } else {
                $data["animation"] = "mfp-zoom-in";
            }
        }
        if (isset($this->request->post["borderimage"])) {
            $data["borderimage"] = $this->request->post["borderimage"];
        } else {
            if (!empty($module_info["borderimage"])) {
                $data["borderimage"] = $module_info["borderimage"];
            } else {
                $data["borderimage"] = "";
            }
        }
        if (isset($this->request->post["text"])) {
            $data["text"] = $this->request->post["text"];
        } else {
            if (!empty($module_info)) {
                $data["text"] = $module_info["text"];
            } else {
                $data["text"] = "0";
            }
        }
        if (isset($this->request->post["textbg"])) {
            $data["textbg"] = $this->request->post["textbg"];
        } else {
            if (!empty($module_info["textbg"])) {
                $data["textbg"] = $module_info["textbg"];
            } else {
                $data["textbg"] = "#fff";
            }
        }
        if (isset($this->request->post["texthover"])) {
            $data["texthover"] = $this->request->post["texthover"];
        } else {
            if (!empty($module_info)) {
                $data["texthover"] = $module_info["texthover"];
            } else {
                $data["texthover"] = "1";
            }
        }
        $this->load->model("localisation/language");
        $data["languages"] = $this->model_localisation_language->getLanguages();
        $data["lang"] = $this->language->get("lang");
        $this->load->model("tool/image");
        if (isset($this->request->post["gallery_image"])) {
            $gallery_images = $this->request->post["gallery_image"];
        } else {
            if (!empty($module_info["gallery_image"])) {
                $gallery_images = $module_info["gallery_image"];
            } else {
                $gallery_images = array();
            }
        }
        $data["gallery_images"] = array();
        foreach ($gallery_images as $key => $value) {
            foreach ($value as $gallery_image) {
                if (is_file(DIR_IMAGE . $gallery_image["image"])) {
                    $image = $gallery_image["image"];
                    $thumb = $gallery_image["image"];
                } else {
                    $image = "";
                    $thumb = "no_image.png";
                }
                $data["gallery_images"][$key][] = array("gallery_image_description" => $gallery_image["gallery_image_description"], "image" => $image, "thumb" => $this->model_tool_image->resize($thumb, 100, 100), "sort_order" => $gallery_image["sort_order"]);
            }
        }
        $data["placeholder"] = $this->model_tool_image->resize("no_image.png", 100, 100);
        if (isset($this->request->post["status"])) {
            $data["status"] = $this->request->post["status"];
        } else {
            if (!empty($module_info)) {
                $data["status"] = $module_info["status"];
            } else {
                $data["status"] = "";
            }
        }
        $data["header"] = $this->load->controller("common/header");
        $data["column_left"] = $this->load->controller("common/column_left");
        $data["footer"] = $this->load->controller("common/footer");
        $this->response->setOutput($this->load->view("extension/module/galleryrb", $data));
    }
    protected function validate()
    {
        if (!$this->user->hasPermission("modify", "extension/module/galleryrb")) {
            $this->error["warning"] = $this->language->get("error_permission");
        }
        if (utf8_strlen($this->request->post["name"]) < 3 || 64 < utf8_strlen($this->request->post["name"])) {
            $this->error["name"] = $this->language->get("error_name");
        }
        if (!$this->request->post["thumb_width"]) {
            $this->error["width_thumb"] = $this->language->get("error_width_thumb");
        }
        if (!$this->request->post["thumb_height"]) {
            $this->error["height_thumb"] = $this->language->get("error_height_thumb");
        }
        if (!$this->request->post["popup_width"]) {
            $this->error["width_popup"] = $this->language->get("error_width_popup");
        }
        if (!$this->request->post["popup_height"]) {
            $this->error["height_popup"] = $this->language->get("error_height_popup");
        }
        return !$this->error;
    }
}

?>