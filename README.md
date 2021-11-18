# Poster Child

A simple Slack app for posting scheduled messages to the Technologists of Color Slack workspace.

## Next Steps

* Add routing capabilities to trigger publishing from [Airplane.dev service](https://blog.airplane.dev/) rather than CLI
* Deploy to DigitalOcean App Platform for testing (doesn't have a true crontab so we can't trigger publishing from CLI command like I've been doing locally)
* Add `publish_after` date comparison functionality to determine if Post is ready to publish
* Expand routing to respond to the `Read the Whole Story` Action Button for tracking interactions