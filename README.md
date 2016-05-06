# Changelog - A CLI for changelog management

Changelog helps you as a developer manage your changelog file. Making it easier to update on the fly.

## Documentation

You can now add to a changelog file with one simple command while tagging your repo with a new semantic version.

```
clg log:create {project Name}
clg log:add {major|minor|patch} --added="A new changelog" --changed="Minor refactoring of X" --fixed="Nothing" --removed="Dropped method Y"
```

## Installation

Make sure you have `~/.composer/vendor/bin/` in your PATH.

* Run the following command:

```bash
composer global require mlantz/changelog
```

## License

Changelog is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests

Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
