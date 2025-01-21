# TagPilot AI – Smart Auto-Tagging
- Contributors: smit08
- Donate link: https://profiles.wordpress.org/smit08/
- Tags: AI, AutoTag, Tag Management, AutoTagging, Auto Tag
- Tested up to: 6.7
- Stable tag: 1.0.0
- License: GPLv2 or later
- License URI: http://www.gnu.org/licenses/gpl-2.0.html

TagPilot AI is your intelligent companion for effortless content organization.

### Description

TagPilot AI is your intelligent companion for effortless content organization. This plugin automatically analyzes your posts, assigns relevant tags, and saves you time.

---

### External services

TagPilot AI utilizes the Dandelion API to analyze post content and intelligently suggest relevant tags using AI-powered natural language processing. This automation helps improve content organization, discoverability, and user experience with minimal effort.

Tagpilot AI plugin sends the post content to Dandelion to analyze the text and suggest the tags by AI.

This service is provided by "spaziodati.eu": [privacy policy](https://dandelion.eu/api-privacy/), [terms of use](https://dandelion.eu/tos/).

---

## Features

- **AI-Powered Tag Suggestions**: Automatically generate relevant tags for your posts based on their content.
- **Seamless Integration**: Works effortlessly with the WordPress editor to tag posts during the save process.
- **Dandelion API Integration**: Leverages advanced natural language processing from the Dandelion API for accurate tag recommendations.

---

### Requirements
To use this plugin, you need a valid Dandelion API key. Follow these steps to obtain your API key:

1. Create an account at https://dandelion.eu/.
2. Retrieve your API key from your Dandelion profile.
3. Enter the API key in the plugin settings.

---

## Installation

1. Download the plugin and upload the folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Navigate to the "TagPilot AI – Smart Auto-Tagging" page in the WordPress admin menu.

---

## How to use it?

1. The first step is to add the API key in the plugin settings. Without the API key, the plugin will not function.
2. After adding the API key, you can start creating or updating posts.
3. Once the post is finalized, clicking "Save Draft," "Publish," or "Save" will trigger the plugin to scan the post content and assign relevant tags using natural language processing via the Dandelion API.

---

## Roadmap

- Implement auto-tagging functionality for custom post types.
- Introduce the ability to exclude specific posts from auto-tagging.
- Add a CRON job to scan newly created or updated posts and assign tags on an hourly basis.

---

## Contribution

Feel free to submit issues or pull requests on the [GitHub repository](https://github.com/Smit2808/tagpilot-ai).

---

## License

This plugin is open-source and licensed under the MIT License.
